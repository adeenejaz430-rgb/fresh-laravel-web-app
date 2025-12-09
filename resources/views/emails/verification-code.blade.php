@php
    $title = $type === 'login' ? 'Login Verification Code' : 'Password Reset Verification Code';
@endphp

<div style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;padding:20px;border:1px solid #e0e0e0;border-radius:5px;">
    <h2 style="color:#333;">{{ $title }}</h2>
    <p>Your verification code is:</p>
    <div style="background-color:#f5f5f5;padding:10px;font-size:24px;font-weight:bold;text-align:center;letter-spacing:5px;margin:20px 0;">
        {{ $code }}
    </div>
    <p>This code will expire in 15 minutes.</p>
    <p>If you didn't request this code, please ignore this email.</p>
</div>
