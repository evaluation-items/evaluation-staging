<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\User;


class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email:rfc,dns',
        ]);

        $key = 'reset-password:' . $request->ip();

        // if (RateLimiter::tooManyAttempts($key, 3)) {
        //     return back()->withErrors([
        //         'email' => 'Too many reset attempts. Try again later.'
        //     ]);
        // }

       // RateLimiter::hit($key, 600);

        $user = User::where('email', $request->email)->first();
        // Anti-enumeration protection
        if (!$user || !$user->hasVerifiedEmail()) {
            return back()->withSuccess('If the account exists and is eligible, a reset link has been sent.');
        }

        Password::sendResetLink(
            $request->only('email')
        );

          return back()->withSuccess('If the account exists and is eligible, a reset link has been sent.');
       }
}
