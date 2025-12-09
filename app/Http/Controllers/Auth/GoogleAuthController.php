<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    // Step 1: redirect to Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Step 2: handle Google callback
    public function callback()
    {
        try {
            // Retrieve user info from Google
            $googleUser = Socialite::driver('google')->user();

            // Try to find existing user
            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            // If user doesn’t exist → create it
            if (!$user) {
                $user = User::create([
                    'name'      => $googleUser->getName(),
                    'email'     => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password'  => bcrypt(Str::random(32)),
                ]);
            }

            // If google_id was empty previously, update it
            if (!$user->google_id) {
                $user->google_id = $googleUser->getId();
                $user->save();
            }

            // LOGIN THE USER
            Auth::login($user, true);

            // REDIRECT TO HOME PAGE
            return redirect()->route('shop.home');

        } catch (\Exception $e) {

            return redirect()->route('login')
                ->with('error', 'Google login failed: ' . $e->getMessage());
        }
    }
}
