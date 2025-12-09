<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display the cart page
     */
    public function index()
    {
        $cart = [];
        
        // If logged in, get from database
        if (auth()->check()) {
            $dbCart = auth()->user()->cartProducts;
            
            foreach ($dbCart as $product) {
                $cart[$product->id] = [
                    'id'    => $product->id,
                    'name'  => $product->name,
                    'slug'  => $product->slug,
                    'price' => $product->price,
                    'image' => $product->images[0] ?? '/flower.png',
                    'qty'   => $product->pivot->quantity,
                ];
            }
        } else {
            // Guest: use session
            $cart = session()->get('cart', []);
        }
        
        // Calculate totals
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['qty'];
        }
        
        $shipping = $subtotal > 50 ? 0 : 10; // Free shipping over $50
        $tax = $subtotal * 0.10; // 10% tax
        $total = $subtotal + $shipping + $tax;
        
        // Get categories for navbar
        $categories = Category::orderBy('name')->get();
        
        return view('shop.cart.index', compact(
            'cart',
            'subtotal',
            'shipping',
            'tax',
            'total',
            'categories'
        ));
    }

    /**
     * Add product to cart
     */
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $quantity = $request->input('quantity', 1);

        // Check stock
        if ($product->quantity < $quantity) {
            return back()->with('error', 'Not enough stock available.');
        }

        // If user is logged in, use database
        if (auth()->check()) {
            $user = auth()->user();
            $existingCartItem = $user->cartProducts()->where('product_id', $product->id)->first();
            
            if ($existingCartItem) {
                $newQty = $existingCartItem->pivot->quantity + $quantity;
                
                if ($product->quantity < $newQty) {
                    return back()->with('error', 'Not enough stock available.');
                }
                
                $user->cartProducts()->updateExistingPivot($product->id, [
                    'quantity' => $newQty
                ]);
            } else {
                $user->cartProducts()->attach($product->id, [
                    'quantity' => $quantity
                ]);
            }
        } else {
            // Guest users: use session
            $cart = session()->get('cart', []);
            
            if (isset($cart[$product->id])) {
                $newQty = $cart[$product->id]['qty'] + $quantity;
                
                if ($product->quantity < $newQty) {
                    return back()->with('error', 'Not enough stock available.');
                }
                
                $cart[$product->id]['qty'] = $newQty;
            } else {
                $cart[$product->id] = [
                    'id'    => $product->id,
                    'name'  => $product->name,
                    'slug'  => $product->slug,
                    'price' => $product->price,
                    'image' => $product->images[0] ?? '/flower.png',
                    'qty'   => $quantity,
                ];
            }
            
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Product added to cart!');
    }

    /**
     * Update cart item quantity
     */
  public function update(Request $request, $id)
{
    try {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $quantity = $request->input('quantity');
        $product = Product::find($id);

        if (!$product) {
            if ($request->ajax() || $request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Product not found'
                ], 404);
            }
            return back()->with('error', 'Product not found.');
        }

        if ($product->quantity < $quantity) {
            if ($request->ajax() || $request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Not enough stock available'
                ], 400);
            }
            return back()->with('error', 'Not enough stock available.');
        }

        if (auth()->check()) {
            $user = auth()->user();
            $cartItem = $user->cartProducts()->where('product_id', $id)->first();

            if ($cartItem) {
                $user->cartProducts()->updateExistingPivot($id, [
                    'quantity' => $quantity
                ]);
                
                if ($request->ajax() || $request->expectsJson() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true, 
                        'message' => 'Cart updated'
                    ]);
                }
                
                return back()->with('success', 'Cart updated!');
            }

            if ($request->ajax() || $request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Product not found in cart'
                ], 404);
            }
            
            return back()->with('error', 'Product not found in cart.');
            
        } else {
            // Guest: update session
            $cart = session()->get('cart', []);

            if (isset($cart[$id])) {
                $cart[$id]['qty'] = $quantity;
                session()->put('cart', $cart);
                
                if ($request->ajax() || $request->expectsJson() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true, 
                        'message' => 'Cart updated'
                    ]);
                }
                
                return back()->with('success', 'Cart updated!');
            }

            if ($request->ajax() || $request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Product not found in cart'
                ], 404);
            }
            
            return back()->with('error', 'Product not found in cart.');
        }
    } catch (\Exception $e) {
        \Log::error('Cart update error: ' . $e->getMessage());
        
        if ($request->ajax() || $request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => false, 
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
        
        return back()->with('error', 'An error occurred while updating the cart.');
    }
}

    /**
     * Remove item from cart
     */
  public function remove(Request $request, $id)
{
    try {
        if (auth()->check()) {
            $user = auth()->user();
            $cartItem = $user->cartProducts()->where('product_id', $id)->first();

            if ($cartItem) {
                $user->cartProducts()->detach($id);
                
                // ALWAYS return JSON for AJAX requests
                if ($request->ajax() || $request->expectsJson() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true, 
                        'message' => 'Item removed from cart'
                    ]);
                }
                
                return back()->with('success', 'Item removed from cart!');
            }

            if ($request->ajax() || $request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Product not found in cart'
                ], 404);
            }
            
            return back()->with('error', 'Product not found in cart.');
            
        } else {
            // Guest: remove from session
            $cart = session()->get('cart', []);

            if (isset($cart[$id])) {
                unset($cart[$id]);
                session()->put('cart', $cart);
                
                if ($request->ajax() || $request->expectsJson() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true, 
                        'message' => 'Item removed from cart'
                    ]);
                }
                
                return back()->with('success', 'Item removed from cart!');
            }

            if ($request->ajax() || $request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Product not found in cart'
                ], 404);
            }
            
            return back()->with('error', 'Product not found in cart.');
        }
    } catch (\Exception $e) {
        \Log::error('Cart remove error: ' . $e->getMessage());
        
        if ($request->ajax() || $request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => false, 
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
        
        return back()->with('error', 'An error occurred while removing the item.');
    }
}

    /**
     * Clear entire cart
     */
    public function clear()
    {
        // If logged in, clear database cart
        if (auth()->check()) {
            auth()->user()->cartProducts()->detach();
        } else {
            // Guest: clear session
            session()->forget('cart');
        }

        return back()->with('success', 'Cart cleared!');
    }
}