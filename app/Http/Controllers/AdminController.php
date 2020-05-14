<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Http\Controllers\Controller;
use App\Mail\bulkMail;
use App\Mail\contact;
use App\Referral;
use App\Subscription;
use App\Traits\Main;
use App\Transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    use Main;

    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $users = User::where('is_admin', 0)->get();

        $referrals = Referral::ordered()->get()->take(10);

        $transactions = Transaction::ordered()->get()->take(10);

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
        $sub_types = [...['guest', 'individual'], ...$subscriptions];

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

            if ($desc != '') {
                $query->where('desc', 'LIKE', "%{$desc}%");

            }

        })->orderBy('created_at', 'desc');

        //return $pagination;
        //$transactions = $query;

        if ($sub_type == 'guest') {

            $query = $query->filter(function ($transaction) use ($sub_type, $subscriptions) {

                return $transaction->user_id == '';

            });
        }

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

        $trans = Transaction::get();
        //$trans =$transactions;

        $transactions = $transactions->paginate(100);
        $t = $query->get();
        //$transactions = $pagination;
        //return $sub_type;
        //return $transactions;

        $totalDebit = $trans->where('type', 'debit');
        $totalCredit = $trans->where('type', 'credit');

        //$query = $query->get();

        $credit = $t->where('type', 'credit');

        $debit = $t->where('type', 'debit');

        $reasons = Transaction::pluck('reason')->unique();
        $types = Transaction::pluck('type')->unique();

        //return $sub_types;

        $compact = compact('transactions', 'users', 'credit', 'debit', 'from', 'to', 'reason', 'reasons', 'ref', 'totalCredit', 'totalDebit', 'type', 'types', 'sub_type', 'sub_types', 'desc');

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
        $sub_types = [...['individual'], ...$subscriptions];

        $query = Referral::whereBetween('referrals.created_at', [$from, $to])->where(function ($query) use ($ref, $sub_type, $desc) {

            if ($ref != '') {
                $query->where('ref', 'LIKE', "%{$ref}%");

            }
            if ($desc != '') {
                $query->where('desc', 'LIKE', "%{$desc}%");

            }

        })->orderBy('created_at', 'desc');

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

        $transactions = $query->paginate(100);
        $r = $query->get();
        //$transactions = $pagination;
        $referrals = Referral::get();

        $compact = compact('transactions', 'referrals', 'users', 'from', 'to', 'ref', 'sub_type', 'sub_types', 'desc', 'r');

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
        $sub_types = [...['individual'], ...$subs];

        $query = Subscription::join('transactions', 'subscriptions.transaction_id', '=', 'transactions.id')->whereBetween('subscriptions.created_at', [$from, $to])->where(function ($query) use ($ref) {

            if ($ref != '') {
                $query->where('transactions.ref', 'LIKE', "%{$ref}%");

            }

        })->select('subscriptions.*')->orderBy('subscriptions.created_at', 'desc');

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

        $subscriptions = $query->paginate(100);
        $s = $query->get();
        // $subscriptions = $pagination;

        //$subscriptions = $subscriptions->sortByDesc('created_at');

        //return $subscriptions;

        $compact = compact('subscriptions', 'totalSubscriptions', 'from', 'to', 'ref', 'sub_type', 'sub_types', 's');

        return view('admin.history.subscription', $compact);

    }

    public function searchUsers()
    {
        $search = request()->search ?? '';
        $sub_type = request()->sub_type ? request()->sub_type : '';
        $subscriptions = array_keys(config('settings.subscriptions'));
        $sub_types = [...['individual'], ...$subscriptions];

        $query = User::where(function ($query) use ($search, $sub_type) {
            if ($search != '') {
                $query->where('first_name', 'LIKE', "%{$search}%")->orWhere('last_name', 'LIKE', "%{$search}%")->orWhere('email', 'LIKE', "%{$search}%")->orWhere('login', 'LIKE', "%{$search}%");

            }

            if ($sub_type == 'individual') {
                return $query->where('is_reseller', 0);
            }

        })->orderBy('created_at', 'desc'); /* ->filter(function ($user) use ($type) {
        if ($user->lastSub()) {
        return $user->lastSub()->name == $type;
        }
        }) */;

        if (in_array($sub_type, $subscriptions)) {

            $users = $query->filter(function ($user) use ($sub_type, $subscriptions) {
                if ($user->lastSub()) {
                    return $user->lastSub()->name == $sub_type;
                }
            });
        }

        //$pagination = $query->paginate(100);
        $users = $query->paginate(100);
        $u = $query->get();

        $admins = $users->where('is_admin', 1);
        $nonAdmins = $users->where('is_admin', 0);

        $compact = compact('users', 'u', 'sub_type', 'sub_types', 'search', 'admins', 'nonAdmins');

        return view('admin.search.user', $compact);
    }

    public function fundWallet(User $user)
    {
        $this->validate(request(), [
            'amount' => 'required|numeric|min:1',
            //'password' => ["required", new checkOldPassword(Auth::user())],
        ]);

        $user->update([
            'balance' => $user->balance + request()->amount,
        ]);

        $amount = request()->amount;

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

            $user->notify(new alert($desc));

        } catch (\Throwable $th) {
            //throw $th;
        }

        return $this->jsonWebBack('success', "{$amount} added to wallet"/* , $user->routePath() */);

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
        $content = request()->content;

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
}