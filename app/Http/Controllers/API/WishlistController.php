<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class WishlistController extends Controller
{
    // GET /api/wishlist
    public function index(Request $request)
    {
        $user = $request->user();

        $wishlist = $user->wishlistProducts()->get();

        return response()->json($wishlist);
    }

    // POST /api/wishlist  { productId }
    public function store(Request $request)
    {
        $request->validate([
            'productId' => 'required|integer|exists:products,id',
        ]);

        $user    = $request->user();
        $product = Product::findOrFail($request->productId);

        $already = $user->wishlistProducts()->where('product_id', $product->id)->exists();

        if ($already) {
            return response()->json(['message' => 'Already in wishlist'], 200);
        }

        $user->wishlistProducts()->attach($product->id);

        return response()->json(['message' => 'Added to wishlist successfully'], 200);
    }

    // DELETE /api/wishlist { productId }
    public function destroy(Request $request)
    {
        $request->validate([
            'productId' => 'required|integer|exists:products,id',
        ]);

        $user = $request->user();
        $user->wishlistProducts()->detach($request->productId);

        return response()->json(['message' => 'Removed from wishlist'], 200);
    }
}
