<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Http\Controllers\Controller;
use App\Mail\bulkMail;
use App\Mail\contact;
use App\Referral;
use App\Subscription;
use App\Traits\BillPayment;
use App\Traits\Main;
use App\Traits\MoniWalletBill;
use App\Traits\Notify;
use App\Transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    use Main, Notify, BillPayment;

    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $users = User::where('is_admin', 0)->get();

        $referrals = Referral::ordered()->get()->take(config("settings.recent_page"));

        $transactions = Transaction::ordered()->get()->take(config("settings.recent_page"));

        $compact = compact('users', 'transactions', 'referrals');

        //return $users;
        //request()->session()->flash('error', 'Pin Successfully Changed');

        return view('admin.index', $compact);
    }

    public function walletHistory()
    {

        $from = request()->from ? Carbon::parse(request()->from) : now();
        $from = $from->startOfDay();
        $to = request()->to ? Carbon::parse(request()->to) : now();
        $to = $to->endOfDay();
        $reason = request()->reason ? request()->reason : '';
        $ref = request()->ref ? request()->ref : '';
        $desc = request()->desc ? request()->desc : '';
        $type = request()->type ? request()->type : '';
        $sub_type = request()->sub_type ? request()->sub_type : '';

        $users = User::where('is_admin', 0)->get();
        $subscriptions = array_keys(config('settings.subscriptions'));
        //$sub_types = [...['guest', 'individual'], ...$subscriptions];
        $sub_types = array_merge(['guest', 'individual'], $subscriptions);
        //return $sub_types;

        $query = Transaction::whereBetween('transactions.created_at', [$from, $to])->where(function ($query) use ($reason, $ref, $type, $sub_type, $desc) {
            if ($reason != '') {
                $query->where('reason', $reason);

            }
            if ($ref != '') {
                $query->where('ref', 'LIKE', "%{$ref}%");

            }

            if ($type != '') {
                $query->where('type', $type);

            }

        })->where(function ($query) use ($desc) {
            if ($desc != '') {
                $query->where('desc', 'LIKE', "%({$desc})%")->orWhere('desc', 'LIKE', "%{$desc} %")->orWhere('desc', 'LIKE', "%{$desc}-%");

            }

        })->orderBy('id', 'desc')->get();

        //return $pagination;
        //$transactions = $query;

        if ($sub_type == 'guest') {

            $query = $query->filter(function ($transaction) use ($sub_type, $subscriptions) {

                return $transaction->user_id == '';

            });
        }

        if ($sub_type == 'individual') {

            $query = $query->filter(function ($transaction) use ($sub_type, $subscriptions) {

                return $transaction->user ? $transaction->user->is_reseller == 0 : false;

            });
        }

        if (in_array($sub_type, $subscriptions)) {

            $query = $query->filter(function ($transaction) use ($sub_type, $subscriptions) {
                if ($transaction->user && $transaction->user->lastSub()) {
                    return $transaction->user->lastSub()->name == $sub_type;
                }
            });
        }

        $trans = Transaction::get();
        //$trans =$transactions;

        $transactions = $query;
        //$t = $query->get();
        //$transactions = $pagination;
        //return $sub_type;
        //return $transactions;

        $totalDebit = $trans->where('type', 'debit');
        $totalCredit = $trans->where('type', 'credit');

        //$totalProfit = $totalDebit->sum('profit');

        $credit = $transactions->where('type', 'credit');

        $debit = $transactions->where('type', 'debit');

        //$tranProfit = $debit->sum('profit');

        $reasons = Transaction::pluck('reason')->unique();
        $types = Transaction::pluck('type')->unique();

        //return $sub_types;

        //Profit Calculations
        //return config('settings.subscriptions');
        $profit = [];

        $guest = $transactions->filter(function ($transaction) use ($sub_type, $subscriptions) {

            return $transaction->user_id == '';

        })->where('type', 'debit');

        $sum = $guest->sum('amount');

        $profit['guest'] = [
            //'discount' => $value['bills']['airtime'][$desc],
            'debit' => $sum,
            'profit' => $guest->sum('profit'),
        ];

        $tran = $transactions->filter(function ($transaction) use ($sub_type, $subscriptions) {

            return $transaction->user ? $transaction->user->is_reseller == 0 : false;

        })->where('type', 'debit');
        $sum = $tran->sum('amount');

        $profit['individual'] = [
            //'discount' => $value['bills']['airtime'][$desc],
            'debit' => $sum,
            'profit' => $tran->sum('profit'),
        ];

        foreach (config('settings.subscriptions') as $key => $value) {
            $tran = $transactions->filter(function ($transaction) use ($key) {
                if ($transaction->user && $transaction->user->lastSub()) {
                    return $transaction->user->lastSub()->name == $key;
                }
            })->where('type', 'debit');

            $sum = $tran->sum('amount');

            $profit[$key] = [
                //'discount' => $value['bills']['airtime'][$desc],
                'debit' => $sum,
                'profit' => $tran->sum('profit'),
            ];
        }

        /* if (in_array($reason, ['airtime', 'data'])) {

        if (in_array($desc, array_keys(config('settings.bills')[$reason]))) {
        $profit['individual']['default'] = config('settings.default')[$reason][$desc];
        $profit['individual']['discount'] = config('settings.individual')['bills'][$reason][$desc];
        $profit['individual']['profit'] = $profit['individual']['debit'] * (($profit['individual']['default'] - $profit['individual']['discount']) / 100);

        $profit['guest']['default'] = config('settings.default')[$reason][$desc];
        $profit['guest']['discount'] = 0;
        $profit['guest']['profit'] = $profit['guest']['debit'] * (($profit['guest']['default'] - $profit['guest']['discount']) / 100);

        foreach (config('settings.subscriptions') as $key => $value) {
        $profit[$key]['default'] = config('settings.default')[$reason][$desc];
        $profit[$key]['discount'] = $value['bills'][$reason][$desc];
        $profit[$key]['profit'] = $profit[$key]['debit'] * (($profit[$key]['default'] - $profit[$key]['discount']) / 100);

        }
        } else {
        $profit = [];
        }
        } else {
        $profit = [];

        } */
        //åreturn $profit;

        $compact = compact('transactions', 'users', 'credit', 'debit', 'from', 'to', 'reason', 'reasons', 'ref', 'totalCredit', 'totalDebit', 'type', 'types', 'sub_type', 'sub_types', 'desc', 'profit');

        return view('admin.history.wallet', $compact);

    }

    public function referralHistory()
    {
        $from = request()->from ? Carbon::parse(request()->from) : now();
        $from = $from->startOfDay();
        $to = request()->to ? Carbon::parse(request()->to) : now();
        $to = $to->endOfDay();
        $ref = request()->ref ? request()->ref : '';
        $desc = request()->desc ? request()->desc : '';

        $sub_type = request()->sub_type ? request()->sub_type : '';

        $users = User::get();
        $subscriptions = array_keys(config('settings.subscriptions'));
        //$sub_types = [...['individual'], ...$subscriptions];
        $sub_types = array_merge(['individual'], $subscriptions);

        $query = Referral::whereBetween('referrals.created_at', [$from, $to])->where(function ($query) use ($ref, $sub_type, $desc) {

            if ($ref != '') {
                $query->where('ref', 'LIKE', "%{$ref}%");

            }
            if ($desc != '') {
                $query->where('desc', 'LIKE', "%{$desc}%");

            }

        })->orderBy('id', 'desc')->get();

        //$transactions = $query;

        if ($sub_type == 'individual') {

            $query = $query->filter(function ($transaction) use ($sub_type, $subscriptions) {

                return $transaction->user->is_reseller == 0;

            });
        }

        if (in_array($sub_type, $subscriptions)) {

            $query = $query->filter(function ($transaction) use ($sub_type, $subscriptions) {
                if ($transaction->user && $transaction->user->lastSub()) {
                    return $transaction->user->lastSub()->name == $sub_type;
                }
            });
        }

        $transactions = $query;
        //$r = $query->get();
        //$transactions = $pagination;
        $referrals = Referral::get();

        $compact = compact('transactions', 'referrals', 'users', 'from', 'to', 'ref', 'sub_type', 'sub_types', 'desc');

        return view('admin.history.referral', $compact);

    }
    public function subscriptionHistory()
    {
        $from = request()->from ? Carbon::parse(request()->from) : now();
        $from = $from->startOfDay();
        $to = request()->to ? Carbon::parse(request()->to) : now();
        $to = $to->endOfDay();
        $ref = request()->ref ? request()->ref : '';

        $sub_type = request()->sub_type ? request()->sub_type : '';

        $totalSubscriptions = Subscription::get();
        //return $totalSubscriptions;

        $subs = array_keys(config('settings.subscriptions'));
        //$sub_types = [...['individual'], ...$subs];
        $sub_types = array_merge(['guest', 'individual'], $subs);

        $query = Subscription::join('transactions', 'subscriptions.transaction_id', '=', 'transactions.id')->whereBetween('subscriptions.created_at', [$from, $to])->where(function ($query) use ($ref) {

            if ($ref != '') {
                $query->where('transactions.ref', 'LIKE', "%{$ref}%");

            }

        })->select('subscriptions.*')->orderBy('subscriptions.id', 'desc')->get();

        //$subscriptions = $query;

        if ($sub_type == 'individual') {

            $$query = $$query->filter(function ($subscription) use ($sub_type, $subscriptions) {

                return $subscription->transaction->user->is_reseller == 0;

            });
        }

        if (in_array($sub_type, $subs)) {

            $$query = $query->filter(function ($subscription) use ($sub_type, $subs) {
                if ($subscription->transaction->user && $subscription->transaction->user->lastSub()) {
                    return $subscription->transaction->user->lastSub()->name == $sub_type;
                }
            });
        }

        // return $sub_type;

        $subscriptions = $query;
        //$s = $query->get();
        // $subscriptions = $pagination;

        //$subscriptions = $subscriptions->sortByDesc('created_at');

        //return $subscriptions;

        $compact = compact('subscriptions', 'totalSubscriptions', 'from', 'to', 'ref', 'sub_type', 'sub_types');

        return view('admin.history.subscription', $compact);

    }

    public function searchUsers()
    {
        $this->authorize('viewAny', User::class);

        $search = request()->search ?? '';
        $sub_type = request()->sub_type ? request()->sub_type : '';
        $roles = Role::pluck('name')->toArray();
        $subscriptions = array_keys(config('settings.subscriptions'));
        //$sub_types = [...['individual'], ...$subscriptions];
        $sub_types = array_merge(['individual'], $subscriptions, $roles);
        //return $sub_types;

        $query = User::where(function ($q) use ($search, $sub_type) {
            if ($search != '') {
                $q->where('first_name', 'LIKE', "%{$search}%")->orWhere('last_name', 'LIKE', "%{$search}%")->orWhere('email', 'LIKE', "%{$search}%")->orWhere('login', 'LIKE', "%{$search}%");

            }

            if ($sub_type == 'individual') {
                return $q->where('is_reseller', 0);
            }

        })->orderBy('id', 'desc')->take(config("settings.per_page"))->get();
        //->paginate(1);
        /* ->filter(function ($user) use ($type) {
        if ($user->lastSub()) {
        return $user->lastSub()->name == $type;
        }
        }) */;

        //return ($query);

        if (in_array($sub_type, $subscriptions)) {

            $query = $query->filter(function ($user) use ($sub_type, $subscriptions) {
                if ($user->lastSub()) {
                    return $user->lastSub()->name == $sub_type;
                }
            });
        }

        if (in_array($sub_type, $roles)) {

            $query = $query->filter(function ($user) use ($sub_type) {
                return $user->hasRole($sub_type);
            });
        }

        //$pagination = $query->paginate(config("settings.per_page"));

        $users = $query;
        //$u = $query; //->get(); //->get();
        //return $users;

        $admins = $users->where('is_admin', 1);
        $nonAdmins = $users->where('is_admin', 0);

        //$totalUsers = User::get();
        $totalAdmins = User::where('is_admin', 1);
        $totalNonAdmins = User::where('is_admin', 0);

        $compact = compact('users', 'totalAdmins', 'totalNonAdmins', 'sub_type', 'sub_types', 'search', 'admins', 'nonAdmins');

        return view('admin.search.user', $compact);
    }

    public function fundWallet(User $user)
    {
        $this->authorize('fund', $user);

        $this->validate(request(), [
            'amount' => 'required|numeric|min:1',
            //'password' => ["required", new checkOldPassword(Auth::user())],
        ]);

        $amount = request()->amount;

        Main::fundBonus($user, $amount);

        $user->update([
            'balance' => $user->balance + request()->amount,
        ]);

        $desc = "Wallet funding of {$amount} by " . Auth::user()->login;

        $tran = Transaction::create([
            'amount' => $amount,
            'balance' => $user->balance,
            'type' => 'credit',
            'desc' => "{$desc}",
            'ref' => generateRef($user),
            'user_id' => $user->id,
            'is_online' => 0,
            //'reason' => 'top-up',
        ]);

        $activity = Activity::create([
            'user_id' => $user->id,
            'admin_id' => auth()->user()->id,
            'summary' => $desc,
        ]);

        $this->giveReferralBonus($user);

        try {

            $user->notify(new alert($desc, $tran));

        } catch (\Throwable $th) {
            //throw $th;
        }

        return $this->jsonWebBack('success', "{$amount} added to wallet"/* , $user->routePath() */);

    }

    public function getSms()
    {
        $this->authorize('massMail', User::class);

        return view('admin.sms');
    }

    public function postSms()
    {
        $this->authorize('massMail', User::class);
        $this->validate(request(), [
            'subject' => 'required|String',
            'content' => 'required|String',
            'sms' => 'required|boolean',
            'balance' => 'nullable|required_with:sign|numeric',
            'sign' => 'nullable|required_with:balance|in:<=,>=',
        ]);

        $users = User::where('is_admin', 0);
        $subject = request()->subject;
        $content = request()->content . motto();
        $balance = request()->balance;
        $sign = request()->sign;

        //return $sign;

        $numbers = $users->where('number', '!=', '')->where(function ($q) use ($balance, $sign) {
            if ($balance != '') {
                $q->where('balance', $sign, $balance);
            }

        })->pluck('number');

        //return $numbers;
        $numbers = formatPhoneNumberArray(implode(',', $numbers->toArray()));

        if (request()->sms) {
            MoniWalletBill::sms(implode(',', $numbers), $content, generateRef());
        }

        $this->appTopic('global', $content, $subject);

        return $this->jsonWebBack('success', "Mass Message Sent");

    }

    public function getContact()
    {
        $this->authorize('massMail', User::class);

        return view('admin.contact');
    }
    public function contact()
    {
        $this->authorize('massMail', User::class);
        $this->validate(request(), [
            'subject' => 'required|String',
            'content' => 'required|String',
        ]);
        //return request()->all();

        $users = User::where('is_admin', 0)->get();
        $subject = request()->subject;
        $content = request()->content . motto();

        foreach ($users as $user) {
            Mail::to($user->email)->later(now()->addMinutes(5), new bulkMail($subject, $content));
        }

        return $this->jsonWebBack('success', "Mass Email Sent");

    }

    public function clearTestData()
    {
        /* $user = User::where('login', 'user')->first();
    $user->subscriptions()->delete();
    $user->transactions()->delete();
    $user->userActivities()->delete();
    $user->referrals()->delete();
    $user->update([
    'is_reseller' => 0,
    ]);
    //$user->delete();
    return redirect($user->routePath()); */

    }

    public function assignRole(User $user)
    {
        $this->authorize('manageRoles', User::class);

        if (!$user->is_admin || $user->hasRole('super admin')) {
            abort(403);
        }

        $validate = [

            'role' => 'required|string|not_in:1',

        ];

        $this->validate(request(), $validate);
        $role = Role::findByName(request()->role);
        if (!$role) {
            abort(404);
        }
        if ($user->hasRole($role->name)) {
            $user->removeRole($role->name);
            $status = 'removed from';
        } else {
            $user->assignRole($role->name);
            $status = 'added to';
        }

        $activityTo = Activity::create([
            'user_id' => $user->id,
            'admin_id' => auth()->user()->id,
            'summary' => "Role " . $role->name . " {$status} user {$user->login}",
        ]);

        return $this->jsonWebBack('success', "Role " . $role->name . " {$status} user {$user->login}");

    }

    public function assignPermission(User $user)
    {
        $this->authorize('manageRoles', User::class);

        if (!$user->is_admin || $user->hasRole('super admin')) {
            abort(403);
        }

        $validate = [

            'permission' => 'required|string',

        ];

        $this->validate(request(), $validate);
        $permission = Permission::findByName(request()->permission);
        if (!$permission) {
            abort(404);
        }

        if ($user->hasPermissionTo($permission->name)) {
            $user->revokePermissionTo($permission->name);
            $status = 'removed from';
        } else {
            $user->givePermissionTo($permission->name);
            $status = 'added to';
        }

        $activityTo = Activity::create([
            'user_id' => $user->id,
            'admin_id' => auth()->user()->id,
            'summary' => "Permission " . $permission->name . " {$status} user {$user->login}",
        ]);

        return $this->jsonWebBack('success', "Permission " . $permission->name . " {$status} user {$user->login}");

    }

    public function getCreateAdmin()
    {
        $this->authorize('manageRoles', User::class);

        return view('admin.admin.create');
    }

    public function createAdmin()
    {
        $data = [
            'login' => ['required', 'string', 'max:15', 'unique:users', 'alpha_dash'],
            'first_name' => 'required|string|max:20',
            'last_name' => 'required|string|max:20',
            'pic' => 'nullable|image|max:250',
            'number' => 'required|numeric|digits_between:10,11|unique:users',
            'email' => 'required|email:rfc,dns,strict,spoof,filter|unique:users',
            'password' => 'required|min:5|string|confirmed',
            'role' => 'required|not_in:1',
            'gender' => 'required|in:male,female',
            'address' => 'required|string|max:150',

        ];

        $this->validate(request(), $data);

        $user = User::create(request()->except('pic', 'password_confirmation', 'role'));

        $user->assignRole(request()->role);

        $user->update([
            'is_admin' => 1,
        ]);

        if (request()->pic) {

            $id = request()->file('pic');
            $path = $id->store('profile', ['disk' => 'public']);

            $user->update([
                'profile' => $path,
            ]);

        }

        return $this->jsonWebBack('success', "Admin {$user->login} with {$user->roles->first()->name} role created");

    }
}