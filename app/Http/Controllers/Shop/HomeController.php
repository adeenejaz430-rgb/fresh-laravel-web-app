<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    /**
     * Show storefront homepage.
     */
    public function index()
    {
        // All categories for navbar dropdown / homepage sections
        $categories = Category::orderBy('name')->get();

        // Recently added products (last 2 days)
        $recentProducts = Product::where('created_at', '>=', now()->subDays(2))
            ->orderByDesc('created_at')
            ->take(12)
            ->get();

        // "Featured" – just latest products for now (no is_featured column)
        $featuredProducts = Product::orderByDesc('created_at')
            ->take(12)
            ->get();

        // "Popular" – random products for now (no views column)
        $popularProducts = Product::inRandomOrder()
            ->take(8)
            ->get();

        return view('shop.home', compact(
            'categories',
            'recentProducts',
            'featuredProducts',
            'popularProducts'
        ));
    }
}
