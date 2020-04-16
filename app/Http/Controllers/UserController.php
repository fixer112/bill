<?php

namespace App\Http\Controllers;

//use App\Traits\Main;
use App\Activity;
use App\Referral;
//use App\Traits\Referral;
use App\Subscription;
use App\Traits\Payment;
use App\Transaction;
use App\User;
use Carbon\Carbon;

class UserController extends Controller
{
    use Payment/*, Referral , Main */;

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
        $this->authorize('subscribe', $user);

        return view('user.subscribe.new');

    }

    public function getUpgrade(User $user)
    {
        $this->authorize('subscribe', $user);

        return view('user.subscribe.upgrade');

    }

    public function subscribe($reference)
    {
        $tranx = $this->validatePayment($reference);

        if (is_array($tranx) && isset($tranx['error'])) {
            return $this->jsonWebBack(request(), 'error', $tranx['error']);
        }

        //$user = User::find($tranx->data->metadata->user_id);

        if (!$user = User::find($tranx->data->metadata->user_id)) {
            return $this->jsonWebBack(request(), 'error', 'User does not exist');

        }

        if ($tranx->data->metadata->reason != 'subscription') {
            return $this->jsonWebBack(request(), 'error', 'Payment is not for subscription');

        }

        $lastSub = $user->subscriptions->last();

        /* if (!auth()->user()->is_admin && $tranx->data->metadata->user_id != auth()->user()->id) {
        abort(403);
        } */

        if (Transaction::where('ref', $reference)->first()) {
            return $this->jsonWebRedirect(request(), 'error', "Payment {$reference} already approved", auth()->user()->routePath());

        }

        $amount = $tranx->data->amount / 100;

        if ($tranx->data->metadata->upgrade == true) {
            $newSubAmount = config("settings.subscriptions.{$lastSub->name}.amount") + $amount;
            foreach (config("settings.subscriptions") as $key => $value) {

                if ($value['amount'] == $newSubAmount) {
                    $newSub = $key;
                    break;
                }
            }
            if (!isset($newSub)) {
                return $this->jsonWebBack(request(), 'error', 'No subscription available for the amount paid');

            }

            $bonus = config("settings.subscriptions.{$newSub}.bonus");

            $desc = "Upgraded from {$user->subscription} to {$newSub}";
            //$user->update(['subscription' => $newSub]);

        } else {
            foreach (config("settings.subscriptions") as $key => $value) {
                if ($value['amount'] == $amount) {
                    $newSub = $key;
                    break;
                }
            }

            if (!isset($newSub)) {
                return $this->jsonWebBack(request(), 'error', 'No subscription available for the amount paid');
            }

            $desc = "Subscribed to {$newSub}";
            //$newSubAmount = config("settings.subscriptions.{$newSub}.amount");
            $bonus = config("settings.subscriptions.{$newSub}.bonus");
            $user->update(['balance' => $user->balance + calPercentageAmount($amount, $bonus)]);

            $user->giveReferralBounus($amount, 'Subscription bunus');

        }

        $limit = config("settings.subscriptions.{$newSub}.rate_limit");

        $user->update(['rate_limit' => $limit]);

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
            'last_sub' => $lastSub ? $lastSub : null,
            'transaction_id' => $tran->id,
        ]);

        $activity = Activity::create([
            'user_id' => $tranx->data->metadata->user_id,
            'admin_id' => auth()->user()->id,
            'summary' => $desc,
        ]);

        //$this->app($user, $activity->summary, 'Payment Approved');

        return $this->jsonWebRedirect(request(), 'success', $activity->summary, "/user/$activity->user_id");

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

        $transactions = Transaction::where('user_id', $user->id)->whereBetween('created_at', [$from, $to])->where(function ($query) use ($reason, $ref) {
            if ($reason != '') {
                $query->where('reason', $reason);

            }
            if ($ref != '') {
                $query->where('ref', 'LIKE', "%{$ref}%");

            }

        })->paginate(1);

        $query = Transaction::where('user_id', $user->id)->whereBetween('created_at', [$from, $to])->where(function ($query) use ($reason, $ref) {
            if ($reason != '') {
                $query->where('reason', $reason);

            }
            if ($ref != '') {
                $query->where('ref', 'LIKE', "%{$ref}%");

            }

        })->get();

        $totalDebit = $user->transactions->where('type', 'debit');
        $totalCredit = $user->transactions->where('type', 'credit');

        $credit = $query->where('type', 'credit');

        $debit = $query->where('type', 'debit');

        $reasons = Transaction::pluck('reason')->unique();

        //$transactions->paginate(1);

        $compact = compact('transactions', 'credit', 'debit', 'from', 'to', 'reason', 'reasons', 'ref', 'totalCredit', 'totalDebit');

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

    public function getAirtime(User $user)
    {
        $this->authorize('view', $user);

        return view('user.bill.airtime');
    }

    public function getData(User $user)
    {
        $this->authorize('view', $user);

        return view('user.bill.data');
    }
}
