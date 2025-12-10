<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Product listing page
     * - search
     * - category filter
     * - price range
     * - sorting
     */
    public function index(Request $request)
    {
        // ✅ Use 'categoryRelation' since that's what you named it in the Product model
        $query = Product::query()->with('categoryRelation');

        // --- read filters from request ---
        $search       = $request->input('search');
        $categorySlug = $request->input('category');
        $minPrice     = $request->input('min_price');
        $maxPrice     = $request->input('max_price');
        $sort         = $request->input('sort', 'newest');

        // --- search by name / description ---
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // --- filter by category slug ---
        if (!empty($categorySlug)) {
            // ✅ Filter by the 'category' column which stores slug
            $query->where('category', $categorySlug);
        }

        // --- price range ---
        if ($minPrice !== null && $minPrice !== '') {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice !== null && $maxPrice !== '') {
            $query->where('price', '<=', $maxPrice);
        }

        // --- sorting: newest / price_asc / price_desc ---
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;

            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;

            default: // newest
                $query->orderBy('created_at', 'desc');
                break;
        }

        // 👇 This is what your Blade expects
        $filteredProducts = $query->paginate(12)->withQueryString();

        // For "X of Y products" counter in Blade
        $products = Product::all();

        // For sidebar / navbar
        $categories = Category::orderBy('name')->get();

        return view('shop.products.index', [
            'filteredProducts' => $filteredProducts,
            'products'         => $products,
            'categories'       => $categories,
            'sort'             => $sort,
            'search'           => $search,
            'categorySlug'     => $categorySlug,
            'minPrice'         => $minPrice,
            'maxPrice'         => $maxPrice,
        ]);
    }

    /**
     * Show single product
     * Make sure your route is like:
     * Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
     */
  public function show(Product $product)
{
    $product->increment('views');
    $product->load('categoryRelation');
    
    $relatedProducts = Product::with('categoryRelation')
        ->where('category', $product->category)
        ->where('id', '!=', $product->id)
        ->latest()
        ->take(8)
        ->get();

    $categories = Category::orderBy('name')->get();
    
    // Check if product is in wishlist for authenticated user
    $isWishlisted = false;
    if (auth()->check()) {
        $isWishlisted = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->exists();
    }

    return view('shop.products.show', [
        'product'         => $product,
        'relatedProducts' => $relatedProducts,
        'categories'      => $categories,
        'isWishlisted'    => $isWishlisted,
    ]);
}

    /**
     * Products for a specific category (SEO-friendly).
     * Route example:
     * Route::get('/category/{category:slug}', [ProductController::class, 'category'])->name('category.show');
     */
    public function category(Category $category, Request $request)
    {
        $search = $request->input('search');
        $sort   = $request->input('sort', 'newest');

        // ✅ Filter by slug stored in 'category' column
        $query = Product::with('categoryRelation')
            ->where('category', $category->slug);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;

            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;

            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('shop.categories.show', [
            'category'   => $category,
            'products'   => $products,
            'categories' => $categories,
            'search'     => $search,
            'sort'       => $sort,
        ]);
    }
}