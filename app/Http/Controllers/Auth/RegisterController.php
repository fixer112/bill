<?php

namespace App\Http\Controllers\Auth;

use App\Activity;
use App\Http\Controllers\Controller;
use App\Notifications\UserCreated;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'number' => ['required', 'string', 'unique:users', 'numeric', 'digits_between:10,11'],
            'login' => ['required', 'string', 'max:15', 'unique:users', 'alpha_dash'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:rfc,dns,strict,spoof,filter', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'reseller' => ['required', 'boolean'],
            'gender' => ['required', 'in:male,female'],
            'address' => ['required', 'string', 'max:150'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        $user = User::create([
            'login' => $data['login'],
            'number' => $data['number'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'is_reseller' => $data['reseller'],
            'api_token' => Str::random(60),
            'gender' => $data['gender'],
            'address' => $data['address'],
        ]);

        return $user;

    }

    protected function registered(Request $request, $user)
    {
        /* $amount = 100;
        if (Cookie::has('referral')) {
        $desc = 'Registration Bonus';

        $user->update([
        'referral_balance' => $user->referral_balance + $amount,
        ]);

        Referral::create([
        'user_id' => $user->id,
        'amount' => $amount,
        'balance' => $user->referral_balance,
        'referral_id' => $user->id,
        'level' => 0,
        'desc' => $desc,
        'ref' => generateRef($user),
        ]);

        $user->giveReferralBounus($amount, "{$desc} from {$user->login}", true);
        } */

        //Cookie::queue(Cookie::forget('referral'));
        if (!$user->is_reseller) {
            try {
                $this->reserveAccount($user);
            } catch (\Throwable $th) {
            }
        }

        /* $user->update([
        'account_number' => $reserved['accountNumber'],
        'account_reference' => $reserved['reservationReference'],
        'bank_name' => $reserved['bankName'],
        ]); */

        $activity = Activity::create([
            'user_id' => $user->id,
            'admin_id' => Auth::id(),
            'summary' => 'Account created',
        ]);

        try {
            $user->notify(new UserCreated());
        } catch (\Exception $e) {
            //dump("Mail not sent : {$e->getMessage()}");
        }

        if (request()->wantsJson()) {
            return $user;
        }

        return redirect($user->routePath());

    }

}