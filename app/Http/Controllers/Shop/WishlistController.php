<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WishlistController extends Controller
{
    /**
     * Show wishlist for logged-in user.
     */
    public function index()
    {
        $user = Auth::user();

        $items = Wishlist::with(['product', 'product.categoryRelation'])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        // Debug: Check if products are loading
        // dd($items->toArray());

        return view('shop.wishlist.index', compact('items'));
    }

    /**
     * Add product to wishlist.
     * Route example: POST /wishlist/{product}
     */
    public function store(Product $product, Request $request)
    {
        try {
            $user = Auth::user();

            $exists = Wishlist::where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->exists();

            if ($exists) {
                if ($request->wantsJson() || $request->expectsJson()) {
                    return response()->json([
                        'success' => false, 
                        'message' => 'Product already in wishlist.'
                    ], 200);
                }
                return back()->with('info', 'Product already in wishlist.');
            }

            Wishlist::create([
                'user_id'    => $user->id,
                'product_id' => $product->id,
            ]);

            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Added to wishlist.'
                ], 200);
            }

            return back()->with('success', 'Product added to wishlist.');
            
        } catch (\Exception $e) {
            Log::error('Wishlist store error: ' . $e->getMessage());
            
            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Failed to add to wishlist.'
                ], 500);
            }
            
            return back()->with('error', 'Failed to add to wishlist.');
        }
    }

    /**
     * Remove product from wishlist.
     * Route example: DELETE /wishlist/{product}
     */
    public function destroy(Product $product, Request $request)
    {
        try {
            $user = Auth::user();

            $deleted = Wishlist::where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->delete();

            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Removed from wishlist.'
                ], 200);
            }

            return back()->with('success', 'Product removed from wishlist.');
            
        } catch (\Exception $e) {
            Log::error('Wishlist destroy error: ' . $e->getMessage());
            
            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Failed to remove from wishlist.'
                ], 500);
            }
            
            return back()->with('error', 'Failed to remove from wishlist.');
        }
    }

    /**
     * Clear entire wishlist for logged-in user.
     */
    public function clear(Request $request)
    {
        try {
            $user = Auth::user();

            Wishlist::where('user_id', $user->id)->delete();

            return redirect()->route('wishlist.index')
                ->with('success', 'Your wishlist has been cleared.');
            
        } catch (\Exception $e) {
            Log::error('Wishlist clear error: ' . $e->getMessage());
            
            return back()->with('error', 'Failed to clear wishlist.');
        }
    }
}