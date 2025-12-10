<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('q');
        $status = $request->get('status');

        $query = User::query()
            ->where('role', User::ROLE_USER)  // Use 'user' role constant instead of 'customer'
            ->withCount('orders')
            ->withSum('orders as total_spent', 'total_price');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
                // Removed phone search as 'phone' field doesn't exist in users table
            });
        }

        // Removed status filter as 'status' field doesn't exist in users table
        // If you need status filtering, you can filter by order status or add a status field to users table

        $customers = $query->paginate(15)->withQueryString();

        return view('admin.customers.index', compact('customers', 'search', 'status'));
    }
}
