<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use App\User;
use Illuminate\Support\Facades\Auth;
use Cookie;

class LoginController extends Controller
{
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
    protected function redirectTo()
    {
        $config = config('session');
        Cookie::queue(Cookie::make('user', auth()->user()->remember_token, 600, $config['path'], $config['domain'], $config['secure'], false, false, $config['same_site'] ?? null));
        if (auth()->user()->role == 'admin') {
            return '/admin/discounts';
        }
        return '/';
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

    /**
     * Handle Social login request
     *
     * @return response
     */
    public function socialLogin($social)
    {
        return Socialite::driver($social)->redirect();
    }
    /**
     * Obtain the user information from Social Logged in.
     * @param $social
     * @return Response
     */

    public function handleProviderCallback($social)
    {
        $config = config('session');
        $userSocial = Socialite::driver($social)->user();
        $user = User::where(['email' => $userSocial->getEmail()])->first();
        if($user){
            Auth::login($user, true);
            Cookie::queue(Cookie::make('user', auth()->user()->remember_token, 600, $config['path'], $config['domain'], $config['secure'], false, false, $config['same_site'] ?? null));
            if ($user->role == 'admin') return redirect('/admin/discounts');
            return redirect('/');
        }else{
            return view('auth.register',[
                'name' => $userSocial->getName(),
                'email' => $userSocial->getEmail()
            ]);
        }
    }

    public function logout() {
      $cookie = Cookie::forget('user');
      Auth::logout();
      return redirect('/')->cookie($cookie);
    }
}
