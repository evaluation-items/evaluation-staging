<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use App\Models\User;
use App\Models\User_user_role;
use App\Models\User_user_role_deptid;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;


class LoginController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
      protected function redirectTo(){
          if( Auth()->user()->role == 25) {
              return route('admin.dashboard');
          } 
      }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->middleware('guest')->except('logout');
    }
   
    // public function showLogin()
    // {
      
    //   return view('auth.login');
    // }

    public function userlogout(Request $request) {
      Auth::logout();
      $request->session()->invalidate();
      $request->session()->regenerateToken();
      return redirect()->to('login');
    }

    public function login(Request $request): RedirectResponse{
     
       $input = $request->all();
       $validated = $request->validate([
          'email'=>'required|email',
          'password'=>'required',
          'captcha' => 'required|captcha'
        ] ,['captcha.captcha'=>'Invalid captcha code.']);
        
    
       if( auth()->attempt(array('email'=>$input['email'], 'password'=>$input['password'])) ){
            $user_id= auth()->user()->id;
            $find_user = User::where('email','=',$input['email'])->latest()->first();
           
            if($request->login_user ==  $find_user->login_user){
                if (auth()->user()->role == 25) {
                  return redirect()->route('admin.dashboard');
                }
                if(auth()->user()->role <= 22 && auth()->user()->role_manage == 0){
                    return redirect()->route('dashboard');   // return redirect()->route('users.dashboard');
                }
                if(auth()->user()->role <= 22 && auth()->user()->role_manage == 1){
                  return redirect()->route('gadsec.dashboard');
                }
                
                if((auth()->user()->role == 23 || auth()->user()->role == 24) && auth()->user()->role_manage == 2){
                  return redirect()->route('evaldir.dashboard');
                }
                if(auth()->user()->role_manage == 3 || auth()->user()->role_manage == 4){
                
                  return redirect()->route('evaldd.dashboard');
                }
            }else{
              return redirect('login')->withError("You don't have use diffrent email for diffrent department login. please set proper email and password.");
            }
            
       }else{
           return redirect('login')->withError('Email and password are incorrect.please try again.');
       }
    }

    public function logout(Request $request): RedirectResponse{
      
      Auth::logout();
  
      $request->session()->invalidate();
  
      $request->session()->regenerateToken();
        return redirect('login');
    }
    public function refreshCaptcha()
    {
        return response()->json(['captcha'=> captcha_img('flat')]);
    }
}
