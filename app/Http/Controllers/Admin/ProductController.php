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
            'gallery.*'   => ['image', 'max:4096'], // Each gallery image
        ]);

        $data['slug']     = Str::slug($data['name']);
        $data['featured'] = $request->boolean('featured');

        // Handle main image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        } else {
            $data['image'] = null;
        }

        // Handle multiple gallery images
        $galleryImages = [];
        
        // Add main image as first gallery image if provided
        if (!empty($data['image'])) {
            $galleryImages[] = $data['image'];
        }
        
        // Process multiple gallery images
        if ($request->hasFile('gallery')) {
            $galleryFiles = $request->file('gallery');
            
            // Laravel returns array when multiple files are uploaded with gallery[]
            // But if only one file, it might be a single UploadedFile object
            if (!is_array($galleryFiles)) {
                $galleryFiles = [$galleryFiles];
            }
            
            foreach ($galleryFiles as $file) {
                if ($file && $file->isValid()) {
                    try {
                        $storedPath = $file->store('products', 'public');
                        // Don't add duplicate of main image
                        if (!empty($storedPath) && $storedPath !== $data['image']) {
                            $galleryImages[] = $storedPath;
                        }
                    } catch (\Exception $e) {
                        // Log error but continue processing other files
                        \Log::error('Gallery image upload failed: ' . $e->getMessage());
                    }
                }
            }
        }
        
        // Remove duplicates and set images array
        $data['images'] = array_values(array_unique(array_filter($galleryImages)));

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
            'gallery.*'   => ['image', 'max:4096'], // Each gallery image
        ]);

        $data['slug']     = Str::slug($data['name']);
        $data['featured'] = $request->boolean('featured');

        // Handle main image upload
        if ($request->hasFile('image')) {
            // Delete old main image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $data['image'] = $request->file('image')->store('products', 'public');
        } else {
            // Keep existing main image if not replaced
            unset($data['image']);
        }

        // Handle gallery images - ADD to existing gallery
        $mainImage = $data['image'] ?? $product->image;
        
        // Start with existing images
        $existingImages = $product->images ?? [];
        $oldMainImage = $product->image;
        
        // If main image changed, update it in the gallery
        if ($oldMainImage && $oldMainImage !== $mainImage) {
            // Remove old main image from gallery
            $existingImages = array_filter($existingImages, fn($img) => $img !== $oldMainImage);
        }
        
        // Build gallery array starting with main image
        $galleryImages = [];
        
        // Add main image as first image if it exists
        if ($mainImage) {
            $galleryImages[] = $mainImage;
        }
        
        // Add existing gallery images (excluding main image to avoid duplicates)
        // Also filter out broken/missing images
        foreach ($existingImages as $existingImg) {
            if ($existingImg !== $mainImage && !empty($existingImg)) {
                // Check if file actually exists
                if (Storage::disk('public')->exists($existingImg)) {
                    $galleryImages[] = $existingImg;
                }
            }
        }
        
        // ADD new gallery images (don't replace, just add)
        if ($request->hasFile('gallery')) {
            $galleryFiles = $request->file('gallery');
            
            // Laravel returns array when multiple files are uploaded with gallery[]
            if (!is_array($galleryFiles)) {
                $galleryFiles = [$galleryFiles];
            }
            
            foreach ($galleryFiles as $file) {
                if ($file && $file->isValid()) {
                    try {
                        $storedPath = $file->store('products', 'public');
                        // Don't add if it's the same as main image or already exists
                        if (!empty($storedPath) && $storedPath !== $mainImage && !in_array($storedPath, $galleryImages)) {
                            $galleryImages[] = $storedPath;
                        }
                    } catch (\Exception $e) {
                        // Log error but continue processing other files
                        \Log::error('Gallery image upload failed: ' . $e->getMessage());
                    }
                }
            }
        }
        
        // Clean up: Remove any broken/missing images from the array
        $validGalleryImages = [];
        foreach ($galleryImages as $img) {
            if (!empty($img) && Storage::disk('public')->exists($img)) {
                $validGalleryImages[] = $img;
            }
        }
        
        // Set images array (always update to ensure main image is first)
        if ($mainImage || !empty($validGalleryImages)) {
            $data['images'] = array_values(array_unique($validGalleryImages));
        } else {
            // No images at all
            $data['images'] = [];
        }

        $product->update($data);

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove a specific image from product gallery
     */
    public function removeImage(Request $request, Product $product)
    {
        $request->validate([
            'image_path' => 'required|string',
        ]);

        $imagePath = $request->input('image_path');
        $images = $product->images ?? [];

        // Remove the image from the array
        $images = array_filter($images, fn($img) => $img !== $imagePath);
        $images = array_values($images); // Re-index array

        // Delete the file from storage
        if (Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }

        // Update product images
        $product->update(['images' => $images]);

        // If this was the main image, clear it
        if ($product->image === $imagePath) {
            $product->update(['image' => null]);
        }

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'Image removed successfully.');
    }

    public function destroy(Product $product)
    {
        // Delete all image files
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        // Delete gallery images
        $images = $product->images ?? [];
        foreach ($images as $image) {
            if (Storage::disk('public')->exists($image)) {
                Storage::disk('public')->delete($image);
            }
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}