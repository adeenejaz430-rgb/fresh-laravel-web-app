<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display the blog index page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('shop.blog.index');
    }
}

