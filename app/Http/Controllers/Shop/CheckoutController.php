<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page
     */
    public function index()
    {
        return view('shop.checkout');
    }

    /**
     * Display the success page after checkout
     */
    public function success()
    {
        return view('shop.success');
    }
}