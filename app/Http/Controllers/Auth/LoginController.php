<?php

namespace App\Http\Controllers\Auth;

use App\Activity;
use App\Http\Controllers\Controller;
use App\Http\Resources\User as UserResource;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'login';
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        try {
            if (!$user->is_admin) {
                //$this->reserveAccount($user);
            }
        } catch (\Throwable $th) {
        }

        return redirect($user->routePath());
    }

    public function loginApi()
    {
        $this->validate(request(), [
            'username' => 'required|exists:users,login',
            'password' => 'required',
        ]);
        $credentials = [
            'login' => request()->username,
            'password' => request()->password,
            //'is_active' => 1,
            'is_admin' => 0,
        ];

        if (Auth::attempt($credentials)) {
            if (!Auth::user()->is_active) {
                return ['error' => 'You are suspended'];

            }

            if (Auth::user()->is_reseller && !Auth::user()->lastSub()) {
                return ['error' => 'Please login at' . url('/login') . ' and subscribe to a reseller package or downgrade to an individual account.'];

            }

            if (!Auth::user()->api_token) {
                Auth::user()->update(['api_token' => Str::random(60)]);
            }

            if (!request()->refresh) {

                Activity::create([
                    'user_id' => Auth::id(),
                    'admin_id' => Auth::id(),
                    'Summary' => 'You logged in to mobile app',
                ]);
            }

            try {
                //$this->reserveAccount($user);
            } catch (\Throwable $th) {
            }

            return new UserResource(Auth::user());
        }

        return ['error' => 'Invalid credentials'];

    }
}