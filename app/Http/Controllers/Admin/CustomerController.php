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
        $status = $request->get('status'); // 'active' or 'inactive' if you have such field

        $query = User::query()
            ->where('role', 'customer')    // adjust if you use something else
            ->withCount('orders')
            ->withSum('orders as total_spent', 'total_price');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($status) {
            // assumes 'status' column: 'active' / 'inactive'
            $query->where('status', $status);
        }

        $customers = $query->paginate(15)->withQueryString();

        return view('admin.customers.index', compact('customers', 'search', 'status'));
    }
}
