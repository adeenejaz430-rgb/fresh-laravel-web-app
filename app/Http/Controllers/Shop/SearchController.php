<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Search results page (Blade) – works with ?q=.
     */
    public function index(Request $request)
    {
        $q = $request->input('q');

        $products = Product::query()
            ->when($q, function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            })
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        return view('shop.search.index', [
            'query'    => $q,
            'products' => $products,
        ]);
    }

    /**
     * Optional: JSON endpoint for AJAX search / autocomplete.
     * GET /search/json?q=...
     */
    public function json(Request $request)
    {
        $q = $request->input('q');

        $products = Product::query()
            ->when($q, function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            })
            ->limit(10)
            ->get(['id', 'name', 'slug', 'price', 'thumbnail']);

        return response()->json([
            'success'  => true,
            'data'     => $products,
        ]);
    }
}
