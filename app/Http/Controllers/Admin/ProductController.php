<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
  public function index(Request $request)
{
    $search   = $request->get('q');
    $category = $request->get('category');

    // 👇 Change 'category' to 'categoryRelation'
    $query = Product::query()->with('categoryRelation');

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('slug', 'like', "%{$search}%")
              ->orWhere('id', 'like', "%{$search}%");
        });
    }

    if ($category) {
        $query->where('category', $category);
    }

    $products   = $query->latest()->paginate(15)->withQueryString();
    $categories = Category::orderBy('name')->get();

    return view('admin.products.index', compact('products', 'categories', 'search', 'category'));
}

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            // category slug, not id
            'category'    => ['required', 'string', 'exists:categories,slug'],
            'price'       => ['required', 'numeric', 'min:0'],
            'quantity'    => ['required', 'integer', 'min:0'],
            'description' => ['required', 'string'],
            'featured'    => ['nullable', 'boolean'],
            // optional main image URL (only if you actually use it in DB & fillable)
            'image'       => ['nullable', 'string', 'max:1000'],
            // one URL per line for gallery
            'gallery'     => ['nullable', 'string'],
        ]);

        $data['slug']     = Str::slug($data['name']);
        $data['featured'] = $request->boolean('featured');

        // Convert gallery textarea (one URL per line) into array
        $images = [];
        if (!empty($data['gallery'])) {
            $images = collect(preg_split("/\r\n|\n|\r/", $data['gallery']))
                ->filter()
                ->values()
                ->all();
        }
        unset($data['gallery']);

        // Product model has `casts['images' => 'array']`
        $data['images'] = $images;

        // If you want to use a separate `image` column, make sure it's in Product::$fillable

        $product = Product::create($data);

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();

        // $product->images is already cast to array
        $gallery = collect($product->images ?? [])->join("\n");

        return view('admin.products.edit', compact('product', 'categories', 'gallery'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'category'    => ['required', 'string', 'exists:categories,slug'],
            'price'       => ['required', 'numeric', 'min:0'],
            'quantity'    => ['required', 'integer', 'min:0'],
            'description' => ['required', 'string'],
            'featured'    => ['nullable', 'boolean'],
            'image'       => ['nullable', 'string', 'max:1000'],
            'gallery'     => ['nullable', 'string'],
        ]);

        $data['slug']     = Str::slug($data['name']);
        $data['featured'] = $request->boolean('featured');

        $images = [];
        if (!empty($data['gallery'])) {
            $images = collect(preg_split("/\r\n|\n|\r/", $data['gallery']))
                ->filter()
                ->values()
                ->all();
        }
        unset($data['gallery']);

        $data['images'] = $images;

        $product->update($data);

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
