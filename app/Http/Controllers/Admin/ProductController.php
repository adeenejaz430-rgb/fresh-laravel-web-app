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
        // Validate basic fields
        $data = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'category'        => ['required', 'string', 'exists:categories,slug'],
            'price'           => ['required', 'numeric', 'min:0'],
            'quantity'        => ['required', 'integer', 'min:0'],
            'description'     => ['required', 'string'],
            'featured'        => ['nullable', 'boolean'],
            'image'           => ['nullable', 'image', 'max:4096'], // Main image
            'gallery'         => ['nullable', 'array'],
            'gallery.*'       => ['image', 'max:4096'], // New gallery images
            'delete_images'  => ['nullable', 'array'], // Images to delete
            'delete_images.*' => ['string'], // Image paths to delete
            'delete_main_image' => ['nullable', 'in:0,1'], // Flag to delete main image
        ]);

        // Update basic fields
        $data['slug'] = Str::slug($data['name']);
        $data['featured'] = $request->boolean('featured');

        // Handle main image deletion/replacement (completely separate from gallery)
        $deleteMainImage = $request->input('delete_main_image') == '1';
        
        if ($request->hasFile('image')) {
            // New main image uploaded - replace existing one
            // Delete old main image if exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            // Store new main image
            $data['image'] = $request->file('image')->store('products', 'public');
        } elseif ($deleteMainImage) {
            // Main image marked for deletion and no new image uploaded
            // Delete the file from storage
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            // Clear main image in database
            $data['image'] = null;
        } else {
            // Keep existing main image - don't touch it
            unset($data['image']);
        }

        // Handle gallery images
        // Start with existing gallery images
        $galleryImages = $product->images ?? [];
        
        // Ensure it's an array
        if (!is_array($galleryImages)) {
            $galleryImages = is_string($galleryImages) ? json_decode($galleryImages, true) : [];
            if (!is_array($galleryImages)) {
                $galleryImages = [];
            }
        }

        // Step 1: Remove images marked for deletion
        $imagesToDelete = $request->input('delete_images', []);
        if (!empty($imagesToDelete) && is_array($imagesToDelete)) {
            foreach ($imagesToDelete as $imagePath) {
                // Remove from array
                $galleryImages = array_filter($galleryImages, function($img) use ($imagePath) {
                    return $img !== $imagePath;
                });
                
                // Delete file from storage
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
            // Re-index array after filtering
            $galleryImages = array_values($galleryImages);
        }

        // Step 2: Add new gallery images (append, don't replace)
        if ($request->hasFile('gallery')) {
            $galleryFiles = $request->file('gallery');
            
            // Handle both single file and array of files
            if (!is_array($galleryFiles)) {
                $galleryFiles = [$galleryFiles];
            }
            
            foreach ($galleryFiles as $file) {
                if ($file && $file->isValid()) {
                    try {
                        $storedPath = $file->store('products', 'public');
                        if (!empty($storedPath)) {
                            // Only add if not already in gallery (avoid duplicates)
                            if (!in_array($storedPath, $galleryImages)) {
                                $galleryImages[] = $storedPath;
                            }
                        }
                    } catch (\Exception $e) {
                        \Log::error('Gallery image upload failed: ' . $e->getMessage());
                    }
                }
            }
        }

        // Step 3: Clean up - remove any broken/missing images
        $validGalleryImages = [];
        foreach ($galleryImages as $img) {
            if (!empty($img) && Storage::disk('public')->exists($img)) {
                $validGalleryImages[] = $img;
            }
        }

        // Update gallery images array (only if we have images or explicitly cleared)
        $data['images'] = array_values(array_unique($validGalleryImages));

        // Update product with all changes
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