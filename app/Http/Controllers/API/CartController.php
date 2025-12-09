<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;

class CartController extends Controller
{
    // POST /api/cart  { productId, quantity? }
    public function store(Request $request)
    {
        $request->validate([
            'productId' => 'required|integer|exists:products,id',
            'quantity'  => 'nullable|integer|min:1',
        ]);

        $user     = $request->user();
        $product  = Product::findOrFail($request->productId);
        $quantity = $request->input('quantity', 1);

        // our cart is pivot table cart_items
        $existing = $user->cartProducts()->where('product_id', $product->id)->first();

        if ($existing) {
            $currentQty = $existing->pivot->quantity;
            $user->cartProducts()->updateExistingPivot($product->id, [
                'quantity' => $currentQty + $quantity,
            ]);
        } else {
            $user->cartProducts()->attach($product->id, [
                'quantity' => $quantity,
            ]);
        }

        $cart = $user->cartProducts()->withPivot('quantity')->get();

        return response()->json([
            'success' => true,
            'cart'    => $cart,
        ]);
    }

    // DELETE /api/cart  { productId }
    public function destroy(Request $request)
    {
        $request->validate([
            'productId' => 'required|integer|exists:products,id',
        ]);

        $user = $request->user();
        $user->cartProducts()->detach($request->productId);

        $cart = $user->cartProducts()->withPivot('quantity')->get();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart',
            'cart'    => $cart,
        ]);
    }
}
