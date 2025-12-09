<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    // GET /api/products
    public function index()
    {
        $products = Product::orderByDesc('created_at')->get();

        return response()->json([
            'success' => true,
            'data'    => $products,
        ]);
    }

    // GET /api/products/{id}
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $product,
        ]);
    }

    // POST /api/products
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'required|string|max:255|unique:products,slug',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:0',
            'images'      => 'nullable|array',
            'images.*'    => 'string',
            'category'    => 'required|string|max:255',
            'featured'    => 'nullable|boolean',
            'quantity'    => 'nullable|integer|min:0',
        ]);

        $product = Product::create($data);

        return response()->json([
            'success' => true,
            'data'    => $product,
        ]);
    }

    // PUT /api/products/{id}
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }

        $data = $request->validate([
            'name'        => 'sometimes|required|string|max:255',
            'slug'        => 'sometimes|required|string|max:255|unique:products,slug,' . $product->id,
            'description' => 'sometimes|required|string',
            'price'       => 'sometimes|required|numeric|min:0',
            'images'      => 'nullable|array',
            'images.*'    => 'string',
            'category'    => 'sometimes|required|string|max:255',
            'featured'    => 'nullable|boolean',
            'quantity'    => 'nullable|integer|min:0',
        ]);

        $product->update($data);

        return response()->json([
            'success' => true,
            'data'    => $product,
        ]);
    }

    // DELETE /api/products?id=...
    public function destroyByQuery(Request $request)
    {
        $id = $request->query('id');

        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'Product ID is required',
            ], 400);
        }

        return $this->destroy($id);
    }

    // DELETE /api/products/{id}
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'data'    => $product,
        ]);
    }
}
