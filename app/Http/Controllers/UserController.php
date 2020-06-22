<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Http\Resources\Referral as ReferralResource;
use App\Http\Resources\Transaction as TransactionResource;
use App\Mail\bulkMail;
//use App\Traits\Referral;
use App\Notifications\alert;
use App\Referral;
use App\Rules\checkBalance;
use App\Rules\checkOldPassword;
use App\Subscription;
use App\Traits\BillPayment;
use App\Traits\Main;
use App\Traits\Payment;
use App\Transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        //request()->session()->flash('error', 'Pin Successfully Changed');

        $compact = compact('referrals', 'directReferral', 'indirectReferral');

        return view('user.index', $compact);
    }

    public function getEditUser(User $user)
    {
        $this->authorize('update', $user);

        return view("user.edit");
    }

    public function editUser(User $user)
    {
        $this->authorize('update', $user);

        //return request()->all();
        $data = [
            'first_name' => 'required|string|max:20',
            'last_name' => 'required|string|max:20',
            'pic' => 'nullable|image|max:250',
            'sms_notify' => 'nullable|boolean',
            'gender' => 'nullable|in:male,female',
            'address' => 'nullable|string|max:150',

        ];

        if ($user->can('suspend', $user) && !$user->hasRole('super admin')) {
            $data['is_active'] = 'nullable|boolean';

        }

        if (request()->email != $user->email) {
            $data['email'] = 'required|email:rfc,dns,strict,spoof,filter|unique:users';

        }

        if (Auth::user()->is_admin) {
            if (request()->number != $user->number) {
                $data['number'] = 'required|numeric|digits_between:10,11|unique:users';
            }
            $data['password'] = 'nullable|min:5|string|confirmed';

        } else {
            $data['password'] = 'nullable|min:5|string|confirmed|required_with:old_password';
            $data['old_password'] = ['nullable', 'required_with:password', 'string', new checkOldPassword($user)];

        }

        $this->validate(request(), $data);

        if (request()->pic) {

            if (Storage::disk('public')->has($user->profile)) {
                Storage::disk('public')->delete($user->profile);
            }

            $id = request()->file('pic');
            $path = $id->store('profile', ['disk' => 'public']);

            $user->update([
                'profile' => $path,
            ]);

        }
        if (request()->password) {
            $user->update(['password' => request()->password]);
        }

        $user->update(request()->except('pic', 'old_password', 'password_confirmation', 'password'));

        $activity = Activity::create([
            'user_id' => $user->id,
            'admin_id' => auth()->user()->id,
            'summary' => "Profile was edited",
        ]);

        return $this->jsonWebBack('success', 'Profile Updated');
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

        $isUpgrade = $user->lastSub() ? true : false;
        $this->authorize('upgrade', $user);
        // if (!request()->user->lastSub()) {
        //     abort(503);
        // }
        //return $user->lastSub();

        //return $user->upgradeList();

        return view('user.subscribe.upgrade', compact('isUpgrade'));

    }

    public function subscribe($reference)
    {
        // return $this->jsonWebBack('error', 'Online Payment Currently Disabled');
        if (!env("ENABLE_ONLINE_PAYMENT")) {
            return $this->jsonWebBack('error', 'payment disabled');
        }

        $tranx = $this->validatePayment($reference, 'subscription');

        if ( /* is_array($tranx) && */isset($tranx['error'])) {
            return $this->jsonWebBack('error', $tranx['error']);
        }

        $user = User::find(getRaveMetaValue($tranx['data']['meta'], 'user_id'));

        $lastSub = $user->lastSub();

        /* if (!auth()->user()->is_admin && $tranx->data->metadata->user_id != auth()->user()->id) {
        abort(403);
        } */

        $amount = getRaveMetaValue($tranx['data']['meta'], 'amount'); //removeCharges(($tranx->data->amount / 100), $tranx->data->metadata->amount);

        if (getRaveMetaValue($tranx['data']['meta'], 'upgrade')) {
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

            $user->giveReferralBounus($amount, 'Subscription bonus');

        }

        $limit = config("settings.subscriptions.{$newSub}.rate_limit");

        //$user->update(['rate_limit' => $limit]);
        $user->update([
            'balance' => $user->balance + calPercentageAmount($amount, $bonus),
            'rate_limit' => $limit,
            'is_reseller' => 1,
        ]);

        $tran = Transaction::create([
            'amount' => calPercentageAmount($amount, $bonus),
            'balance' => $user->balance,
            'type' => 'credit',
            'desc' => "{$desc} bonus",
            'ref' => $reference,
            'user_id' => $user->id,
            // 'reason' => 'subscription',
        ]);

        $sub = Subscription::create([
            'amount' => config("settings.subscriptions.{$newSub}.amount"),
            'user_id' => $user->id,
            'name' => $newSub,
            'last_sub' => $lastSub ? $lastSub->name : null,
            'bonus' => calPercentageAmount($amount, $bonus),
            'transaction_id' => $tran->id,
        ]);

        $activity = Activity::create([
            'user_id' => $user->id,
            'admin_id' => $user->id,
            'summary' => $desc,
        ]);

        $this->reserveAccount($user);

        try {

            $user->notify(new alert($desc, $tran));

        } catch (\Throwable $th) {
            //throw $th;
        }

        //$this->app($user, $activity->summary, 'Payment Approved');

        return $this->jsonWebBack('success', $activity->summary/* , "/user/$activity->user_id" */);

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
    public function history(User $user, Transaction $ref)
    {
        //$ref = Transaction::where('ref',$ref);
        return new TransactionResource($ref);
    }
    public function walletHistory(User $user)
    {
        $this->authorize('view', $user);

        $this->validate(request(), [
            'from' => 'nullable|date',
            'to' => 'nullable|date',
        ]);

        $from = request()->from ? Carbon::parse(request()->from) : now();
        $from = $from->startOfDay();
        $to = request()->to ? Carbon::parse(request()->to) : now();
        $to = $to->endOfDay();
        $reason = request()->reason ? request()->reason : '';
        $ref = request()->ref ? request()->ref : '';
        $type = request() ? request()->type : '';

        //return $from;
        if (request()->wantsJson()) {
            // $type = 'debit';
        }

        $q = Transaction::where('user_id', $user->id)->whereBetween('created_at', [$from, $to])->where(function ($query) use ($reason, $ref, $type) {
            if ($reason != '') {
                $query->where('reason', $reason);

            }
            if ($ref != '') {
                $query->where('ref', 'LIKE', "%{$ref}%");

            }

            if ($type != '') {
                $query->where('type', $type);

            }

        })->orderBy('created_at', 'desc'); //->get();

        if (request()->wantsJson()) {
            return TransactionResource::collection($q->get());
        }

        $transactions = $q->get();

        //$transactions = $pagination->sortByDesc('created_at');

        $totalDebit = $user->transactions->where('type', 'debit');
        $totalCredit = $user->transactions->where('type', 'credit');

        //$q = $q->get();

        $credit = $transactions->where('type', 'credit'); //->get();

        $debit = $transactions->where('type', 'debit'); //->get();

        $reasons = $user->transactions->pluck('reason')->unique();
        $types = $user->transactions->pluck('type')->unique();

        //$transactions = $query->paginate(config("settings.per_page"));
        //$transactions->paginate(1);
        //return $transactions;

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

        $query = Referral::where('user_id', $user->id)->whereBetween('created_at', [$from, $to])->where(function ($query) use ($ref) {
            if ($ref != '') {
                $query->where('ref', 'LIKE', "%{$ref}%");

            }

        })->orderBy('created_at', 'desc');

        if (request()->wantsJson()) {
            return ReferralResource::collection($q->get());
        }

        $transactions = $query->get();

        //$transactions = $pagination->sortByDesc('created_at');
        //$referrals = $Referral::get();

        $compact = compact('transactions', 'from', 'to', 'ref');

        return view('user.referral.history', $compact);
    }

    public function getWithdrawReferral(User $user)
    {
        $this->authorize('view', $user);
        return view('user.referral.withdraw');
    }

    public function withdrawReferral(User $user)
    {
        $this->authorize('view', $user);

        $this->validate(request(), [
            'amount' => "required|numeric|min:2000",
            'password' => ["required", new checkOldPassword($user)],
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

        try {

            $user->notify(new alert($desc, $tran));

        } catch (\Throwable $th) {
            //throw $th;
        }

        return $this->jsonWebBack('success', "Withrawal of {$amount} to wallet successfull"/* , $user->routePath() */);

    }

    public function getTransfer(User $user)
    {
        $this->authorize('update', $user);
        return view('user.wallet.transfer');

    }

    public function transfer(User $user)
    {
        $this->authorize('update', $user);
        $this->validate(request(), [
            'amount' => ["required", "numeric", new checkBalance($user), 'min:100'],
            'username' => "required|exists:users,login",
            'password' => ["required", new checkOldPassword($user)],
        ]);

        $u = User::where('login', request()->username)->first();

        $amount = request()->amount;
        //return $user;
        //return $user->balance - $amount;
        $u->update([
            'balance' => $u->balance + $amount,
        ]);

        $user->update([
            'balance' => $user->balance - $amount,
        ]);

        $descTo = "Transfer of {$amount} from {$user->login}";

        $tranTo = Transaction::create([
            'amount' => $amount,
            'balance' => $u->balance,
            'type' => 'credit',
            'desc' => "{$descTo}",
            'ref' => generateRef($u),
            'user_id' => $u->id,
            'reason' => 'transfer',
        ]);

        $activityTo = Activity::create([
            'user_id' => $u->id,
            'admin_id' => auth()->user()->id,
            'summary' => $descTo,
        ]);

        try {

            $u->notify(new alert($descTo, $tranTo));

        } catch (\Throwable $th) {
            //throw $th;
        }

        $descFrom = "Transfer of {$amount} to {$u->login}";

        $tranFrom = Transaction::create([
            'amount' => $amount,
            'balance' => $user->balance,
            'type' => 'debit',
            'desc' => "{$descFrom}",
            'ref' => generateRef($user),
            'user_id' => $user->id,
            'reason' => 'transfer',
        ]);

        $activityFrom = Activity::create([
            'user_id' => $user->id,
            'admin_id' => auth()->user()->id,
            'summary' => $descFrom,
        ]);

        try {

            $u->notify(new alert($descFrom, $tranFrom));

        } catch (\Throwable $th) {
            //throw $th;
        }

        return $this->jsonWebBack('success', "{$descFrom}"/* , $user->routePath() */);

    }

    public function getDebitWallet(User $user)
    {
        $this->authorize('debit', $user);
        return view('user.wallet.debit');
    }

    public function debitWallet(User $user)
    {
        $this->authorize('debit', $user);

        $this->validate(request(), [
            'amount' => ["required", "numeric"],
            'with_desc' => "required|boolean",
            'desc' => "required_if:with_desc,1|string|max:100",
            //'password' => ["required", new checkOldPassword(Auth::user())],
        ]);

        $amount = request()->amount;
        $desc = request()->desc ?? "Account debited with " . currencyFormat($amount);

        $fullDesc = currencyFormat($amount) . " debited from wallet. Description: " . $desc;

        $user->update([
            'balance' => $user->balance - $amount,
        ]);

        if (request()->with_desc) {
            $tran = Transaction::create([
                'amount' => $amount,
                'balance' => $user->balance,
                'type' => 'debit',
                'desc' => "{$fullDesc}",
                'ref' => generateRef($user),
                'user_id' => $user->id,
                'reason' => 'debit',
            ]);
        }

        $activity = Activity::create([
            'user_id' => $user->id,
            'admin_id' => auth()->user()->id,
            'summary' => $desc,
        ]);

        try {

            // $user->notify(new alert($fullDesc, $tran));

        } catch (\Throwable $th) {
            //throw $th;
        }
        return $this->jsonWebBack('success', $fullDesc/* , $user->routePath() */);

    }

    public function getFundWallet(User $user)
    {
        $this->authorize('view', $user);

        //try {
        if (!$user->account_number) {
            $this->reserveAccount($user);
        }

        /* } catch (\Throwable $th) {
        //throw $th;
        } */

        return view('user.wallet.fund');
    }

    public function fundWallet($reference)
    {
        //return $reference;

        if (!env("ENABLE_ONLINE_PAYMENT")) {
            return $this->jsonWebBack('error', 'payment disabled');
        }

        $tranx = $this->validatePayment($reference, 'top-up');
        //return getRaveMetaValue($tranx['data']['meta'], 'reason');
        //return $tranx->json();

        if ( /* is_array($tranx) && */isset($tranx['error'])) {
            return $this->jsonWebBack('error', $tranx['error']);
        }

        $user = User::find(getRaveMetaValue($tranx['data']['meta'], 'user_id'));
        $plathform = getRaveMetaValue($tranx['data']['meta'], 'plathform') ?? 'web';
        //return $user;

        $amount = getRaveMetaValue($tranx['data']['meta'], 'amount'); //removeCharges(($tranx->data->amount / 100), $tranx->data->metadata->amount);

        $desc = "Wallet funding of " . currencyFormat($amount) . " from online payment";

        $user->update([
            'balance' => $user->balance + $amount,
        ]);

        $tran = Transaction::create([
            'amount' => $amount,
            'balance' => $user->balance,
            'type' => 'credit',
            'desc' => "{$desc}",
            'ref' => $reference,
            'user_id' => $user->id,
            'plathform' => $plathform,
            //'reason' => 'top-up',
        ]);

        $activity = Activity::create([
            'user_id' => $user->id,
            'admin_id' => auth()->check() ? auth()->user()->id : 1,
            'summary' => $desc,
        ]);

        $this->giveReferralBonus($user);

        try {

            $user->notify(new alert($desc, $tran, false));

        } catch (\Throwable $th) {
            //throw $th;
        }

        return $this->jsonWebBack('success', currencyFormat($amount) . " added to wallet" /* , $user->routePath() */);

        //return view('user.wallet.fund');
    }

    public function getAirtime(User $user)
    {
        $this->authorize('view', $user);

        //$user->notify(new alert('Testing Alert', Transaction::find(1)));

        //return $this->saveTransaction($user, 'airtime', 1, 'airtime test', $ref, $result);

        return view('user.bill.airtime');
    }

    public function postAirtime(User $user)
    {
        //return $user;
        $this->authorize('view', $user);
        //return request()->all();

        $networks = config("settings.mobile_networks");
        unset($networks['mtn_sme']);
        //unset($networks['mtn_direct']);

        $bills = config("settings.bills.airtime");

        //return $bills;

        $network = request()->network;
        request()->merge(['network' => strtolower(request()->network)]);
        $this->validate(request(), [
            'network' => "required|string|in:" . implode(',', array_keys($networks)),
        ]);

        $network_code = $networks[$network];
        $discount_amount = calDiscountAmount(request()->amount, airtimeDiscount($user)[$network]);

        request()->merge(['discount_amount' => $discount_amount]);

        $data = [

            'amount' => "required|numeric|min:{$bills[$network]['min']}|max:{$bills[$network]['max']}",
            'number' => "required|numeric|digits_between:10,11",
            'discount_amount' => ['required', "numeric", new checkBalance($user)],

        ];

        if (!request()->wantsJson() || request()->plathform == 'app') {
            $data['password'] = ["required", new checkOldPassword($user)];
        }

        $this->validate(request(), $data);

        //return $data;

        $number = nigeriaNumber(request()->number);
        //return $this->isDublicate($user, $discount_amount, 'airtime');
        $desc = "Recharge of " . strtoupper($network) . " " . currencyFormat(request()->amount) . " to " . $number;

        /* $this->validate(request(), [
        'discount_amount' => [new checkBalance($user)],
        ]); */

        if ($this->isDublicate($user, $discount_amount, $desc, 'airtime')) {
            return $this->jsonWebRedirect('error', dublicateMessage(), "user/{$user->id}/airtime");
        }

        $ref = generateRef($user);

        //return $number;
        if ($network == 'mtn_sns') {
            if ($this->isDublicate($user, $discount_amount, $desc, 'airtime')) {
                return $this->jsonWebRedirect('error', dublicateMessage(), "user/{$user->id}/airtime");
            }

            $result = $this->mtnAirtime(request()->amount, $number, $ref);

        } else {
            if ($this->isDublicate($user, $discount_amount, $desc, 'airtime')) {
                return $this->jsonWebRedirect('error', dublicateMessage(), "user/{$user->id}/airtime");
            }

            $result = $this->airtime(request()->amount, $number, $network_code, $ref);
        }

        //return $result;

        //$result = [];

        return $this->saveTransaction($user, 'airtime', $discount_amount, $desc, $ref, $result);

    }

    public function getData(User $user)
    {
        $this->authorize('view', $user);

        return view('user.bill.data');
    }

    public function postData(User $user)
    {
        $this->authorize('view', $user);
        //return request()->all();

        $networks = config("settings.mobile_networks");
        unset($networks['mtn_sns']);
        //unset($networks['mtn']);
        //unset($networks['mtn_direct']);
        $bills = config("settings.bills.data");

        $network = request()->network;
        request()->merge(['network' => strtolower(request()->network)]);
        $this->validate(request(), [
            'network' => "required|string|in:" . implode(',', array_keys($networks)),

        ]);

        $this->validate(request(), [
            'price' => "required|numeric|in:" . implode(',', collect($bills[$network])->pluck('price')->toArray()),
        ]);

        $network_code = $networks[$network];
        $planKey = array_search(request()->price, array_column($bills[$network], 'price'));
        $plan = $bills[$network][$planKey];
        $price = $plan['price'];
        $amount = $plan['topup_amount'];

        $formatAmount = currencyFormat($amount);

        $details = (($plan["id"])) . " - {$formatAmount} - {$plan['validity']}";

        $discount_amount = calDiscountAmount($amount, dataDiscount($user)[$network]);
        request()->merge(['discount_amount' => $discount_amount]);

        $data = [
            'number' => "required|numeric|digits_between:10,11",
            'discount_amount' => ["required", "numeric", new checkBalance($user)],
        ];

        if (!request()->wantsJson() || request()->plathform == 'app') {
            $data['password'] = ["required", new checkOldPassword($user)];
        }

        $this->validate(request(), $data);

        $desc = "Data subscription of " . strtoupper($network) . " " . $details . " to " . request()->number;

        $number = nigeriaNumber(request()->number);

        if ($this->isDublicate($user, $discount_amount, $desc, 'data')) {
            return $this->jsonWebRedirect('error', dublicateMessage(), "user/{$user->id}/data");
        }

        $ref = generateRef($user);

        if ($network == 'mtn_sme') {

            if ($this->isDublicate($user, $discount_amount, $desc, 'data')) {
                return $this->jsonWebRedirect('error', dublicateMessage(), "user/{$user->id}/data");
            }

            $result = $this->dataMtn($price, $number, $network_code, $ref);

        } else {

            if ($this->isDublicate($user, $discount_amount, $desc, 'data')) {
                return $this->jsonWebRedirect('error', dublicateMessage(), "user/{$user->id}/data");
            }

            $result = $this->data($price, $number, $network_code, $ref);
        }

        return $this->saveTransaction($user, 'data', $discount_amount, $desc, $ref, $result);

    }

    public function getCable(User $user)
    {
        $this->authorize('view', $user);

        return view('user.bill.cable');
    }

    public function postCable(User $user)
    {
        $bills = getCable();

        //return request()->all();
        $data = [
            'amount' => "required|numeric",
            'type' => "required|in:" . implode(',', array_keys($bills)),
            'smart_no' => "required|string",
            'invoice_no' => "required_unless:type,startimes",
            'number' => "nullable|numeric|digits_between:10,11",
            'customer_name' => "required_unless:type,startimes",
            'customer_number' => "required_unless:type,startimes",

        ];

        if (!request()->wantsJson() || request()->plathform == 'app') {
            $data['password'] = ["required", new checkOldPassword($user)];
        }

        $this->validate(request(), $data);

        $type = request()->type;
        $planKey = array_search(request()->amount, array_column($bills[$type], 'amount'));
        $plan = $bills[$type][$planKey];
        $charges = calDiscountAmount($plan['charges'], cableDiscount($user)[$type]);
        $price = $plan['price'] + $charges;
        $formatPrice = currencyFormat($price);

        $details = strtoupper($type) . '-' . $plan["name"] . " - {$formatPrice} - {$plan['duration']}";

        $smart_no = request()->smart_no;
        $discount_amount = $price;
        //return $discount_amount;

        request()->merge(['discount_amount' => $discount_amount]);
        $this->validate(request(), [
            'discount_amount' => [new checkBalance($user)],
        ]);

        $ref = generateRef($user);

        $number = request()->number ? nigeriaNumber(request()->number) : $user->nigeria_number;
        $desc = "Cable Subscription of {$details} for smart no {$smart_no} ($number)";

        if ($this->isDublicate($user, $discount_amount, $desc, 'cable')) {
            return $this->jsonWebRedirect('error', dublicateMessage(), "user/{$user->id}/cable");
        }

        if ($type == 'startimes') {
            if ($this->isDublicate($user, $discount_amount, $desc, 'cable')) {
                return $this->jsonWebRedirect('error', dublicateMessage(), "user/{$user->id}/cable");
            }

            $result = $this->startimeCable(request()->amount, $smart_no, $number);

        } else {
            if ($this->isDublicate($user, $discount_amount, $desc, 'cable')) {
                return $this->jsonWebRedirect('error', dublicateMessage(), "user/{$user->id}/cable");
            }

            $result = $this->cable($type, request()->amount, $smart_no, request()->customer_name, request()->customer_number, request()->invoice, $number);
        }

        //return $result;

        if (is_array($result) && isset($result['error'])) {
            return $this->jsonWebRedirect('error', $result['error'], "user/{$user->id}/cable");
        }

        /* if (!isset($result['exchangeReference'])) {
        return $this->jsonWebBack('error', 'An error ocurred');
        } */

        $ref = $result['exchangeReference'] ?? $ref;

        return $this->saveTransaction($user, 'cable', $discount_amount, $desc, $ref, $result);

    }

    public function getElectricity(User $user)
    {
        $this->authorize('view', $user);

        return view('user.bill.electricity');
    }

    public function postElectricity(User $user)
    {
        $this->authorize('view', $user);
        //return request()->all();
        $bills = config("settings.bills.electricity");
        $product = array_search(request()->service, array_column($bills['products'], 'product_id'));
        $min = $product["min_denomination"];
        $max = $product["max_denomination"];

        $data = [
            'amount' => "required|numeric|min:$min|max:$max",
            'type' => "required|in:1,0",
            'meter_no' => "required|string",
            'service' => "required|string",
            'discount_amount' => [new checkBalance($user)],
            //'number' => "required|numeric|digits_between:10,11",
            //'customer_name' => "required_unless:type,startimes",
            //'customer_number' => "required_unless:type,startimes",

        ];

        if (!request()->wantsJson() || request()->plathform == 'app') {
            $data['password'] = ["required", new checkOldPassword($user)];
        }

        $this->validate(request(), $data);

        $amount = request()->amount;
        $meterno = request()->meter_no;
        $service = request()->service;
        $type = request()->type;
        $t = $type == '1' ? 'prepaid' : 'postpaid';
        $a = currencyFormat($amount);

        $multiples = $amount / env('CABLE_DISCOUNT_MULTIPLE', 5000);

        $charges = calDiscountAmount($bills['charges'] * $multiples, electricityDiscount($user));

        $discount_amount = $amount + $charges;

        request()->merge(['discount_amount' => $discount_amount]);
        $this->validate(request(), [
            'discount_amount' => [new checkBalance($user)],
        ]);

        $ref = generateRef($user);

        $desc = "Electricity payment of {$a} for meter no {$meter_no} {$t} ($service)";

        if ($this->isDublicate($user, $discount_amount, $desc, 'electricity')) {
            return $this->jsonWebRedirect('error', dublicateMessage(), "user/{$user->id}/electricity");
        }

        $result = $this->electricity($service, $meterno, $type, $amount, $ref);

        if (is_array($result) && isset($result['error'])) {
            return $this->jsonWebRedirect('error', $result['error'], "user/{$user->id}/cable");
        }

        return $this->saveTransaction($user, 'electricity', $discount_amount, $desc, $ref, $result);

    }
    public function apiReset(User $user)
    {
        $this->authorize('view', $user);
        $user->update([
            'api_token' => Str::random(60),
        ]);

        return $this->jsonWebBack('success', 'Api Key Updated');
    }
    public function apiDocumentation(User $user)
    {
        $this->authorize('view', $user);

        return view("user.api.documentation");

    }

    public function getBalance(User $user)
    {
        return $user->balance;
    }

    public function fetchData()
    {
        return config("settings.bills.data");
    }

    public function updateStatus(User $user)
    {
        $this->authorize('suspend', $user);

        $user->update(['is_active' => !$user->is_active]);

        $activityTo = Activity::create([
            'user_id' => $user->id,
            'admin_id' => auth()->user()->id,
            'summary' => "User {$user->status()}",
        ]);

        try {

            $user->notify(new alert("Your Account is {$user->status()}"));

        } catch (\Throwable $th) {
            //throw $th;
        }

        return $this->jsonWebBack('success', "User {$user->status()}"/* , $user->routePath() */);

    }

    public function getContact(User $user)
    {
        $this->authorize('massMail', User::class);

        return view('user.contact');
    }

    public function contact(User $user)
    {
        $this->authorize('massMail', User::class);
        $this->validate(request(), [
            'subject' => 'required|String',
            'content' => 'required|String',
        ]);
        //return request()->all();

        $subject = request()->subject;
        $content = request()->content;

        Mail::to($user->email)->send(new bulkMail($subject, $content));

        return $this->jsonWebBack('success', "Email Sent");

    }

    public function downgrade(User $user)
    {
        $user->update([
            'is_reseller' => 0,
        ]);

        return $this->jsonWebRedirect('success', "Successfully downgraded to individual account", $user->routePath());

    }

    public function hook()
    {

        $this->validate(request(), [
            'txRef' => 'required|String',
        ]);

        $reference = request()->txRef;

        //if (request()->event == "charge.success") {
        $tranx = $this->validateHookPayment($reference);

        if ( /* is_array($tranx) && */isset($tranx['error'])) {
            return $this->jsonWebBack('error', $tranx['error']);
        }
        //return $tranx->json();
        $reason = getRaveMetaValue($tranx['data']['meta'], 'reason');

        if ($reason == 'top-up') {
            return $this->fundWallet($reference);

        }

        if ($reason == 'subscription') {
            return $this->subscribe($reference);

        }

        if ($reason == 'airtime') {
            return $this->guestAirtime($reference);

        }

        if ($reason == 'data') {
            return $this->guestData($reference);

        }

        //}
    }

    public function updateToken(User $user)
    {
        $this->validate(request(), [
            'app_token' => "required|string",
        ]);
        $user->update(['app_token' => request()->app_token]);
        return $user->app_token;
        return ['success' => 'App Token Updated'];

    }

    public function removeToken(User $user)
    {
        $this->validate(request(), [
            'app_token' => "required|string",
        ]);

        $user->removeAppToken(request()->app_token);

        return $user->app_token;
        return ['success' => 'App Token Updated'];

    }

}
