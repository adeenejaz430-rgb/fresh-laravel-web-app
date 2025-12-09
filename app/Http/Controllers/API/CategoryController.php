<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // GET /api/categories
    public function index()
    {
        return response()->json(Category::all());
    }

    // (optional) GET /api/categories/{id}
    public function show($id)
    {
        return response()->json(Category::findOrFail($id));
    }

    // POST /api/categories
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'slug'  => 'required|string|max:255|unique:categories,slug',
            'image' => 'nullable|string',
        ]);

        $category = Category::create($data);

        return response()->json($category);
    }

    // PUT /api/categories/{id}
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $data = $request->validate([
            'name'  => 'sometimes|required|string|max:255',
            'slug'  => 'sometimes|required|string|max:255|unique:categories,slug,' . $category->id,
            'image' => 'nullable|string',
        ]);

        $category->update($data);

        return response()->json($category);
    }

    // DELETE /api/categories/{id}
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Category deleted']);
    }
}
