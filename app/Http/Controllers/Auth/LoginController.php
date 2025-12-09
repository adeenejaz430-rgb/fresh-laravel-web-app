<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Mail; // for emailing code

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Step 1: check credentials, generate code, email it, store in session
    public function sendCode(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Invalid email or password.');
        }

        $code = random_int(100000, 999999);

        // store in session (or DB if you prefer)
        session([
            'login_email' => $user->email,
            'login_code'  => $code,
        ]);

        // TODO: send code via mail
        // Mail::to($user->email)->send(new LoginVerificationCodeMail($code));

        return back()
            ->with('verification_step', true)
            ->with('email', $user->email);
    }

    // Step 2: verify code & log in
    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code'  => 'required|digits:6',
        ]);

        $email = session('login_email');
        $code  = session('login_code');

        if (! $email || ! $code || $email !== $request->email || (string) $code !== (string) $request->code) {
            return back()
                ->with('verification_step', true)
                ->with('email', $request->email)
                ->with('error', 'Invalid or expired verification code.');
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            return back()->with('error', 'User not found.');
        }

        // clear temp data
        session()->forget(['login_email', 'login_code', 'verification_step']);

        Auth::login($user, true);

        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
