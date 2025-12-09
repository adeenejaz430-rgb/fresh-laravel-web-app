<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display products for a specific category
     */
    public function show(Request $request, $slug)
    {
        // Find category by slug
        $category = Category::where('slug', $slug)->firstOrFail();
        
        // Start query for products in this category
        $query = Product::where('category', $category->slug)
            ->with('categoryRelation');
        
        // Filter: In Stock Only
        if ($request->has('in_stock')) {
            $query->where('quantity', '>', 0);
        }
        
        // Filter: Featured Only
        if ($request->has('featured')) {
            $query->where('featured', true);
        }
        
        // Filter: Price Range
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        
        if ($minPrice !== null && $minPrice !== '') {
            $query->where('price', '>=', $minPrice);
        }
        
        if ($maxPrice !== null && $maxPrice !== '') {
            $query->where('price', '<=', $maxPrice);
        }
        
        // Sorting
        $sort = $request->input('sort', 'newest');
        
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }
        
        // Paginate results
        $products = $query->paginate(12);
        
        // Get min/max prices for this category (for filter display)
        $priceStats = Product::where('category', $category->slug)
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();
        
        $minPrice = $minPrice ?? floor($priceStats->min_price ?? 0);
        $maxPrice = $maxPrice ?? ceil($priceStats->max_price ?? 1000);
        
        return view('shop.categories.show', compact(
            'category',
            'products',
            'minPrice',
            'maxPrice'
        ));
    }
    
    /**
     * Display all categories
     */
    public function index()
    {
        $categories = Category::withCount('products')
            ->orderBy('name')
            ->get();
        
        return view('shop.categories.index', compact('categories'));
    }
}