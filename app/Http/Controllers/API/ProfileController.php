<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    // GET /api/user/profile
    public function show(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json($user);
    }

    // POST /api/user/profile
    public function update(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'name'                => 'required|string|max:255',
            'email'               => 'required|email|unique:users,email,' . $user->id,
            'address.street'      => 'nullable|string|max:255',
            'address.city'        => 'nullable|string|max:255',
            'address.state'       => 'nullable|string|max:255',
            'address.zip'         => 'nullable|string|max:50',
            'address.country'     => 'nullable|string|max:100',
        ]);

        $user->name  = $data['name'];
        $user->email = $data['email'];

        // our DB has flattened address columns
        $addr = $data['address'] ?? [];
        $user->street  = $addr['street']  ?? null;
        $user->city    = $addr['city']    ?? null;
        $user->state   = $addr['state']   ?? null;
        $user->zip     = $addr['zip']     ?? null;
        $user->country = $addr['country'] ?? null;

        $user->save();

        return response()->json([
            'success' => true,
            'user'    => $user,
        ]);
    }
}
