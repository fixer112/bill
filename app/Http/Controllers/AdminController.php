<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Referral;
use App\Transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $users = User::get();

        $referrals = Referral::ordered()->get()->take(10);
        $transactions = Transaction::ordered()->get()->take(10);

        $compact = compact('users', 'transactions', 'referrals');

        //return $users;
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
        $type = request()->type ? request()->type : '';
        $sub_type = request()->sub_type ? request()->sub_type : '';

        $users = User::get();
        $subscriptions = array_keys(config('settings.subscriptions'));
        $sub_types = [...['guest', 'individual'], ...$subscriptions];

        $trans = Transaction::get();

        $query = Transaction::whereBetween('transactions.created_at', [$from, $to])->where(function ($query) use ($reason, $ref, $type, $sub_type) {
            if ($reason != '') {
                $query->where('reason', $reason);

            }
            if ($ref != '') {
                $query->where('ref', 'LIKE', "%{$ref}%");

            }

            if ($type != '') {
                $query->where('type', $type);

            }

        });

        $pagination = $query->paginate(100);
        //return $pagination;
        $transactions = $pagination;

        if ($sub_type == 'guest') {

            $transactions = $pagination->filter(function ($transaction) use ($sub_type, $subscriptions) {

                return $transaction->user->id == '';

            });
        }

        if ($sub_type == 'individual') {

            $transactions = $pagination->filter(function ($transaction) use ($sub_type, $subscriptions) {

                return $transaction->user->is_reseller == 0;

            });
        }

        if (in_array($sub_type, $subscriptions)) {

            $transactions = $pagination->filter(function ($transaction) use ($sub_type, $subscriptions) {
                if ($transaction->user->lastSub()) {
                    return $transaction->user->lastSub()->name == $sub_type;
                }
            });
        }

        //return $sub_type;
        //return $transactions;

        $totalDebit = $trans->where('type', 'debit');
        $totalCredit = $trans->where('type', 'credit');

        $credit = $transactions->where('type', 'credit');

        $debit = $transactions->where('type', 'debit');

        $reasons = Transaction::pluck('reason')->unique();
        $types = Transaction::pluck('type')->unique();

        //return $sub_types;

        $compact = compact('transactions', 'pagination', 'users', 'credit', 'debit', 'from', 'to', 'reason', 'reasons', 'ref', 'totalCredit', 'totalDebit', 'type', 'types', 'sub_type', 'sub_types');

        return view('admin.history.wallet', $compact);

    }

    public function referralHistory()
    {
        $from = request()->from ? Carbon::parse(request()->from) : now();
        $from = $from->startOfDay();
        $to = request()->to ? Carbon::parse(request()->to) : now();
        $to = $to->endOfDay();
        $ref = request()->ref ? request()->ref : '';
        $sub_type = request()->sub_type ? request()->sub_type : '';

        $users = User::get();
        $subscriptions = array_keys(config('settings.subscriptions'));
        $sub_types = [...['individual'], ...$subscriptions];

        $query = Referral::whereBetween('referrals.created_at', [$from, $to])->where(function ($query) use ($ref, $sub_type) {

            if ($ref != '') {
                $query->where('ref', 'LIKE', "%{$ref}%");

            }

        });

        $pagination = $query->paginate(100);
        $transactions = $pagination;

        if ($sub_type == 'individual') {

            $transactions = $pagination->filter(function ($transaction) use ($sub_type, $subscriptions) {

                return $transaction->user->is_reseller == 0;

            });
        }

        if (in_array($sub_type, $subscriptions)) {

            $transactions = $pagination->filter(function ($transaction) use ($sub_type, $subscriptions) {
                if ($transaction->user->lastSub()) {
                    return $transaction->user->lastSub()->name == $sub_type;
                }
            });
        }

        $referrals = Referral::get();

//return $sub_types;

        $compact = compact('transactions', 'pagination', 'referrals', 'users', 'from', 'to', 'ref', 'sub_type', 'sub_types');

        return view('admin.history.referral', $compact);

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

        }); /* ->filter(function ($user) use ($type) {
        if ($user->lastSub()) {
        return $user->lastSub()->name == $type;
        }
        }) */;

        $pagination = $query->paginate(100);
        $users = $pagination;

        if (in_array($sub_type, $subscriptions)) {

            $users = $pagination->filter(function ($user) use ($sub_type, $subscriptions) {
                if ($user->lastSub()) {
                    return $user->lastSub()->name == $sub_type;
                }
            });
        }

        $admins = $users->where('is_admin', 1);
        $nonAdmins = $users->where('is_admin', 0);

        $compact = compact('users', 'pagination', 'sub_type', 'sub_types', 'search', 'admins', 'nonAdmins');

        return view('admin.search.user', $compact);
    }

    public function fundWallet(User $user)
    {
        $this->validate(request(), [
            'amount' => 'required|numeric|min:1',
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

        return $this->jsonWebRedirect('success', "{$amount} added to wallet", $user->routePath());

    }

    public function clearTestData()
    {
        $user = User::where('login', 'user')->first();
        $user->subscriptions()->delete();
        $user->transactions()->delete();
        $user->userActivities()->delete();
        $user->referrals()->delete();
        $user->update([
            'is_reseller' => 0,
        ]);
        //$user->delete();
        return redirect($user->routePath());

    }
}