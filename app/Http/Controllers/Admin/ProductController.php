<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search   = $request->get('q');
        $category = $request->get('category');

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
            'category'    => ['required', 'string', 'exists:categories,slug'],
            'price'       => ['required', 'numeric', 'min:0'],
            'quantity'    => ['required', 'integer', 'min:0'],
            'description' => ['required', 'string'],
            'featured'    => ['nullable', 'boolean'],
            'image'       => ['nullable', 'image', 'max:4096'], // Main image
            'gallery'     => ['nullable', 'array'],
            'gallery.*'   => ['image', 'max:4096'], // Gallery images
        ]);

        // Generate unique slug to prevent duplicate slug errors
        $baseSlug = Str::slug($data['name']);
        $slug = $baseSlug;
        $counter = 1;
        
        // Check if slug exists and make it unique
        while (Product::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        $data['slug'] = $slug;
        $data['featured'] = $request->boolean('featured');

        // Handle main image upload
        $imagePaths = [];
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
            $imagePaths[] = $imagePath; // Add main image to gallery
        } else {
            $data['image'] = null;
        }

        // Handle gallery images upload
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $galleryFile) {
                $galleryPath = $galleryFile->store('products', 'public');
                $imagePaths[] = $galleryPath;
            }
        }

        // Set images array (main image + gallery images)
        $data['images'] = !empty($imagePaths) ? $imagePaths : [];

        // Create product
        $product = Product::create($data);

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
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
            'image'       => ['nullable', 'image', 'max:4096'], // Main image
            'gallery'     => ['nullable', 'array'],
            'gallery.*'   => ['image', 'max:4096'], // Gallery images
        ]);

        // Generate unique slug (only if name changed)
        if ($data['name'] !== $product->name) {
            $baseSlug = Str::slug($data['name']);
            $slug = $baseSlug;
            $counter = 1;
            
            // Check if slug exists (excluding current product) and make it unique
            while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            
            $data['slug'] = $slug;
        }
        
        $data['featured'] = $request->boolean('featured');

        // Handle image updates
        $imagePaths = [];
        $imagesUpdated = false;

        // Handle main image upload
        if ($request->hasFile('image')) {
            // Delete old main image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
            $imagePaths[] = $imagePath;
            $imagesUpdated = true;
        } else {
            // Keep existing main image if it exists
            if ($product->image) {
                $imagePaths[] = $product->image;
            }
            unset($data['image']); // Don't overwrite with null
        }

        // Handle gallery images upload
        if ($request->hasFile('gallery')) {
            // Delete old gallery images if replacing
            if ($product->images && is_array($product->images)) {
                foreach ($product->images as $oldImage) {
                    // Don't delete if it's the main image
                    if ($oldImage !== $product->image) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
            }

            foreach ($request->file('gallery') as $galleryFile) {
                $galleryPath = $galleryFile->store('products', 'public');
                $imagePaths[] = $galleryPath;
            }
            $imagesUpdated = true;
        } else {
            // Keep existing gallery images (excluding main image which is already added)
            if ($product->images && is_array($product->images)) {
                foreach ($product->images as $existingImage) {
                    // Add gallery images that aren't the main image
                    if ($existingImage !== $product->image && !in_array($existingImage, $imagePaths)) {
                        $imagePaths[] = $existingImage;
                    }
                }
            }
        }

        // Update images array if images were changed
        if ($imagesUpdated || $request->hasFile('image')) {
            $data['images'] = array_unique($imagePaths); // Remove duplicates
        }

        $product->update($data);

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        // Delete image file if exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}