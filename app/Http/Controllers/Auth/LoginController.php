<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Rules\CaptchaRule;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
            'captcha'  => ['required', new CaptchaRule],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt(
            ['email' => $request->email, 'password' => $request->password],
            $remember
        )) {
            return redirect('login')
                ->withError('Email and password are incorrect. Please try again.');
        }

        // ? Regenerate session (security)
        $request->session()->regenerate();

        // ? Enforce single session per user
        DB::table('sessions')
            ->where('user_id', Auth::id())
            ->where('id', '!=', session()->getId())
            ->delete();

        $user = Auth::user();

        // ? Role-based redirects (unchanged logic)
        if ($user->role == 25) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role <= 22 && $user->role_manage == 0) {
            return redirect()->route('dashboard');
        }

        if ($user->role <= 22 && $user->role_manage == 1) {
            return redirect()->route('gadsec.dashboard');
        }

        if (
            ($user->role == 23 || $user->role == 24) &&
            $user->role_manage == 2
        ) {
            return redirect()->route('evaldir.dashboard');
        }

        if (in_array($user->role_manage, [3, 4])) {
            return redirect()->route('evaldd.dashboard');
        }

        // fallback (safety)
        return redirect('/');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('login');
    }

    public function refreshCaptcha()
    {
        return response()->json([
            'captcha' => captcha_img('clean')
        ]);
    }
}