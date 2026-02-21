<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Rules\CaptchaRule;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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
     public function forceReset($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('login')->withError("User not found.");
        }

        return view('auth.force-reset', compact('user'));
    }

      public function forceResetSubmit(Request $request)
      {
        try {
            $userId = Crypt::decrypt($request->user_id);
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['error' => 'Invalid request.']);
        }
        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('login')->withErrors(['error' => 'User not found.']);
        }
            $request->validate([
            'email' => [
                'required',
                'email:rfc,dns', // ✅ Better email validation
                Rule::unique('users', 'email')->ignore($userId),

                // ✅ OPTIONAL: Restrict to gov.in only (uncomment if needed)
                // function ($attribute, $value, $fail) {
                //     if (!preg_match('/\.gov\.in$/', $value)) {
                //         $fail('Only government email addresses are allowed.');
                //     }
                // }
            ],
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[a-z]/',      // lowercase
                'regex:/[A-Z]/',      // uppercase
                'regex:/[0-9]/',      // number
            ],
        ]);
    
         $updated = DB::table('public.users')
              ->where('id', $userId)
              ->update([
                  'email'          => $request->email,
                  'password'       => Hash::make($request->password),
                  'is_first_login' => false,
                  'email_verified_at' => now(),
              ]);

          if ($updated === 0) {
              return back()->withErrors(['error' => 'Nothing was updated']);
          }
          Auth::logout();

          return redirect('login')->withSuccess('Credentials updated. Please login with new email & password.');
      }
}