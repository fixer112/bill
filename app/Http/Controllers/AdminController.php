<?php

namespace App\Http\Controllers;

use App\Referral;
use App\Transaction;
use App\User;
use Carbon\Carbon;

class AdminController extends Controller
{
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
        //$subscriptions = config('settings.subscriptions');
        $sub_types = ['guest', 'individual', 'reseller'];

        $trans = Transaction::get();

        $query = Transaction::leftJoin('users', 'users.id', 'transactions.user_id')->join('subscriptions', 'users.id', 'subscriptions.user_id')->whereBetween('transactions.created_at', [$from, $to])->where(function ($query) use ($reason, $ref, $type, $sub_type) {
            if ($reason != '') {
                $query->where('reason', $reason);

            }
            if ($ref != '') {
                $query->where('ref', 'LIKE', "%{$ref}%");

            }

            if ($type != '') {
                $query->where('type', $type);

            }

            if ($sub_type != '') {
                if ($sub_type == 'guest') {
                    $query->where('users.id', '');
                }
                if ($sub_type == 'individual') {
                    $query->where('users.is_reseller', 0);

                }
                if ($sub_type == 'reseller') {
                    $query->where('users.is_reseller', 1);

                }

            }

        });

        $transactions = $query->paginate(100);

        $totalDebit = $trans->where('type', 'debit');
        $totalCredit = $trans->where('type', 'credit');

        $credit = $query->where('type', 'credit');

        $debit = $query->where('type', 'debit');

        $reasons = Transaction::pluck('reason')->unique();
        $types = Transaction::pluck('type')->unique();

        //return $sub_types;

        $compact = compact('transactions', 'users', 'credit', 'debit', 'from', 'to', 'reason', 'reasons', 'ref', 'totalCredit', 'totalDebit', 'type', 'types', 'sub_type', 'sub_types');

        return view('admin.history.wallet', $compact);

    }

    public function referralHistory()
    {

    }
}