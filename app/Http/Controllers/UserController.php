<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Referral;
use App\Subscription;
//use App\Traits\Referral;
use App\Traits\BillPayment;
use App\Traits\Main;
use App\Traits\Payment;
use App\Transaction;
use App\User;
use Carbon\Carbon;

class UserController extends Controller
{

    use Payment, Main, BillPayment/*, Referral , Main */;

    public function index(User $user)
    {
        $this->authorize('view', $user);

        //return $parents = $user->findAndSaveAllParents();
        //return $user->getParentReferral;

        //return $user->getReferralLink();
        //return $user->getReferralChildren();
        //return $user->getReferralParents();

        //return getReferralLevel(19999);
        // return $user->calDiscount();

        //return $user->getReferralLevel();
        //$lastSub = $user->subscriptions->last();
        $referrals = $user->getReferralChildren();
        $directReferral = $referrals->where('level', 1);
        $indirectReferral = $referrals->where('level', '!=', 1);

        $compact = compact('referrals', 'directReferral', 'indirectReferral');

        return view('user.index', $compact);
    }

    public function getSubscribe(User $user)
    {
        //$this->authorize('subscribe', $user);

        return view('user.subscribe.new');

    }

    public function subscriptions(User $user)
    {
        $this->authorize('reseller', $user);
        return view('user.subscribe.history');

    }

    public function getUpgrade(User $user)
    {
        $this->authorize('upgrade', $user);
        //return $user->upgradeList();

        return view('user.subscribe.upgrade');

    }

    public function subscribe($reference)
    {
        $tranx = $this->validatePayment($reference, 'subscription');

        if (is_array($tranx) && isset($tranx['error'])) {
            return $this->jsonWebBack('error', $tranx['error']);
        }

        $user = User::find($tranx->data->metadata->user_id);

        $lastSub = $user->lastSub();

        /* if (!auth()->user()->is_admin && $tranx->data->metadata->user_id != auth()->user()->id) {
        abort(403);
        } */

        $amount = removeCharges(($tranx->data->amount / 100), $tranx->data->metadata->amount);

        if ($tranx->data->metadata->upgrade) {
            $newSubAmount = config("settings.subscriptions.{$lastSub->name}.amount") + $amount;
            foreach (config("settings.subscriptions") as $key => $value) {

                if ($value['amount'] == $newSubAmount) {
                    $newSub = $key;
                    break;
                }
            }
            if (!isset($newSub)) {
                return $this->jsonWebBack('error', 'No subscription available for the amount paid');

            }

            $bonus = config("settings.subscriptions.{$newSub}.bonus");

            $desc = "Upgraded from {$lastSub->name} to {$newSub}";
            //$user->update(['subscription' => $newSub]);

        } else {
            foreach (config("settings.subscriptions") as $key => $value) {
                if ($value['amount'] == $amount) {
                    $newSub = $key;
                    break;
                }
            }

            if (!isset($newSub)) {
                return $this->jsonWebBack('error', 'No subscription available for the amount paid');
            }

            $desc = "Subscribed to {$newSub}";
            //$newSubAmount = config("settings.subscriptions.{$newSub}.amount");
            $bonus = config("settings.subscriptions.{$newSub}.bonus");

            $user->giveReferralBounus($amount, 'Subscription bunus');

        }

        $limit = config("settings.subscriptions.{$newSub}.rate_limit");

        //$user->update(['rate_limit' => $limit]);
        $user->update([
            'balance' => $user->balance + calPercentageAmount($amount, $bonus),
            'rate_limit' => $limit,
        ]);

        $tran = Transaction::create([
            'amount' => calPercentageAmount($amount, $bonus),
            'balance' => $user->balance,
            'type' => 'credit',
            'desc' => "{$desc} bonus",
            'ref' => $tranx->data->reference,
            'user_id' => $tranx->data->metadata->user_id,
            // 'reason' => 'subscription',
        ]);

        $sub = Subscription::create([
            'amount' => config("settings.subscriptions.{$newSub}.amount"),
            'user_id' => $tranx->data->metadata->user_id,
            'name' => $newSub,
            'last_sub' => $lastSub ? $lastSub->name : null,
            'bonus' => calPercentageAmount($amount, $bonus),
            'transaction_id' => $tran->id,
        ]);

        $activity = Activity::create([
            'user_id' => $tranx->data->metadata->user_id,
            'admin_id' => auth()->user()->id,
            'summary' => $desc,
        ]);

        //$this->app($user, $activity->summary, 'Payment Approved');

        return $this->jsonWebRedirect('success', $activity->summary, "/user/$activity->user_id");

    }

    public function Activity(User $user)
    {
        $this->authorize('view', $user);
        $from = now();
        $to = now();

        if (request()->from) {
            $from = Carbon::parse(request()->from);
        }
        if (request()->to) {
            $to = Carbon::parse(request()->to);
        }

        $userActivities = $user->userActivities;
        $activityType = $user->isMode() ? $userActivities->merge($user->adminActivities) : $userActivities;

        $activities = $activityType->whereBetween('created_at', [$from->startOfDay(), $to->endOfDay()]);

        //return $activities;
        $compact = compact('activities', 'from', 'to');

        if (request()->expectsJson()) {
            //unset($compact['investments']);
            $compact['activities'] = ActivityResource::collection($activities->sortByDesc('created_at'));
            return $compact;
        }

        return view('user.activity', $compact);

    }

    public function walletHistory(User $user)
    {
        $this->authorize('view', $user);

        $from = request()->from ? Carbon::parse(request()->from) : now();
        $from = $from->startOfDay();
        $to = request()->to ? Carbon::parse(request()->to) : now();
        $to = $to->endOfDay();
        $reason = request()->reason ? request()->reason : '';
        $ref = request()->ref ? request()->ref : '';
        $type = request()->type ? request()->type : '';

        $transactions = Transaction::where('user_id', $user->id)->whereBetween('created_at', [$from, $to])->where(function ($query) use ($reason, $ref, $type) {
            if ($reason != '') {
                $query->where('reason', $reason);

            }
            if ($ref != '') {
                $query->where('ref', 'LIKE', "%{$ref}%");

            }

            if ($type != '') {
                $query->where('type', $type);

            }

        })->paginate(1);

        $query = Transaction::where('user_id', $user->id)->whereBetween('created_at', [$from, $to])->where(function ($query) use ($reason, $ref, $type) {
            if ($reason != '') {
                $query->where('reason', $reason);

            }
            if ($ref != '') {
                $query->where('ref', 'LIKE', "%{$ref}%");

            }

            if ($type != '') {
                $query->where('type', $type);

            }

        })->get();

        $totalDebit = $user->transactions->where('type', 'debit');
        $totalCredit = $user->transactions->where('type', 'credit');

        $credit = $query->where('type', 'credit');

        $debit = $query->where('type', 'debit');

        $reasons = Transaction::pluck('reason')->unique();
        $types = Transaction::pluck('type')->unique();

        //$transactions->paginate(1);

        $compact = compact('transactions', 'credit', 'debit', 'from', 'to', 'reason', 'reasons', 'ref', 'totalCredit', 'totalDebit', 'type', 'types');

        return view('user.wallet.history', $compact);
    }

    public function referralHistory(User $user)
    {
        $this->authorize('view', $user);

        $from = request()->from ? Carbon::parse(request()->from) : now();
        $from = $from->startOfDay();
        $to = request()->to ? Carbon::parse(request()->to) : now();
        $to = $to->endOfDay();
        $ref = request()->ref ? request()->ref : '';

        $transactions = Referral::where('user_id', $user->id)->whereBetween('created_at', [$from, $to])->where(function ($query) use ($ref) {
            if ($ref != '') {
                $query->where('ref', 'LIKE', "%{$ref}%");

            }

        })->paginate(100);

        $query = Referral::where('user_id', $user->id)->whereBetween('created_at', [$from, $to])->where(function ($query) use ($ref) {
            if ($ref != '') {
                $query->where('ref', 'LIKE', "%{$ref}%");

            }

        })->get();

        $referrals = $query;

        $compact = compact('transactions', 'from', 'to', 'referrals', 'ref');

        return view('user.referral.history', $compact);
    }

    public function getWithdrawReferral(User $user)
    {
        $this->authorize('view', $user);
        return view('user.referral.withdraw');
    }

    public function withdrawReferral(User $user)
    {
        $this->validate(request(), [
            'amount' => "required|numeric|min:1000",
        ]);

        $amount = request()->amount;

        if ($amount > $user->referral_balance) {
            //return 'error';
            return $this->jsonWebBack('error', 'Insuficcient Referral Balance');
        }
        //return;

        $desc = "Wallet funding of {$amount} from referral wallet";
        
        $user->update([
            'balance' => $user->balance + $amount,
            'referral_balance' => $user->referral_balance - $amount,
        ]);

        $tran = Transaction::create([
            'amount' => $amount,
            'balance' => $user->balance,
            'type' => 'credit',
            'desc' => "{$desc}",
            'ref' => generateRef($user),
            'user_id' => $user->id,
            //'reason' => 'top-up',
        ]);

        $activity = Activity::create([
            'user_id' => $user->id,
            'admin_id' => auth()->user()->id,
            'summary' => $desc,
        ]);

        

        return $this->jsonWebRedirect('error', "Withrawal of {$amount} to wallet successfull", $user->routePath());

    }

    public function getFundWallet(User $user)
    {
        $this->authorize('view', $user);
        return view('user.wallet.fund');
    }

    public function fundWallet($reference)
    {

        $tranx = $this->validatePayment($reference, 'top-up');
        // return \json_encode($tranx);

        if (is_array($tranx) && isset($tranx['error'])) {
            return $this->jsonWebBack('error', $tranx['error']);
        }

        $user = User::find($tranx->data->metadata->user_id);

        $amount = removeCharges(($tranx->data->amount / 100), $tranx->data->metadata->amount);

        $desc = "Wallet funding of {$amount} from online payment";

         $user->update([
            'balance' => $user->balance + $amount,
        ]);

        $tran = Transaction::create([
            'amount' => $amount,
            'balance' => $user->balance,
            'type' => 'credit',
            'desc' => "{$desc}",
            'ref' => $tranx->data->reference,
            'user_id' => $tranx->data->metadata->user_id,
            //'reason' => 'top-up',
        ]);

        $activity = Activity::create([
            'user_id' => $tranx->data->metadata->user_id,
            'admin_id' => auth()->user()->id,
            'summary' => $desc,
        ]);

       

        return $this->jsonWebRedirect('success', "{$amount} added to wallet", $user->routePath());

        //return view('user.wallet.fund');
    }

    public function getAirtime(User $user)
    {
        $this->authorize('view', $user);

        return view('user.bill.airtime');
    }

    public function postAirtime(User $user)
    {
        $this->authorize('view', $user);

        $networks = config("settings.mobile_networks");
        $bills = config("settings.bills.airtime");

        //return $bills[request()->network];
        $this->validate(request(), [
            'network' => "required|string|in:" . implode(',', array_keys($networks)),
            'amount' => "required|numeric|min:{$bills[request()->network]['min']}|max:{$bills[request()->network]['max']}",
            'number' => "required|string",
            'network_code' => "required|string",
            'discount_amount' => "required|numeric",
        ]);
        
        if(request()->discount_amount > $user->balance){
            return $this->jsonWebBack('error', 'Insufficient Fund');
        }

        $result = $this->airtime(request()->amount, request()->number, request()->network_code);

        if (is_array($result) && isset($result['error'])) {
            return $this->jsonWebBack('error', $result['error']);
        }
        $user->update([
            'balance' => $user->balance - request()->discount_amount,
        ]);

        $desc = "Recharge of ".strtoupper(request()->network)." ".currencyFormat(request()->amount)." to ".request()->number;

        $tran = Transaction::create([
            'amount' => request()->discount_amount,
            'balance' => $user->balance,
            'type' => 'debit',
            'desc' => "{$desc}",
            'ref' => generateRef($user),
            'user_id' => $user->id,
            'reason' => 'airtime',
        ]);

        $activity = Activity::create([
            'user_id' => $user->id,
            'admin_id' => auth()->user()->id,
            'summary' => $desc,
        ]);

        

        return $this->jsonWebRedirect('error', $desc, $user->routePath());



        //return request()->all();

    }

    public function getData(User $user)
    {
        $this->authorize('view', $user);

        return view('user.bill.data');
    }
}