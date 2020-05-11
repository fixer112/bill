<?php

namespace App\Http\Controllers;

use App\Activity;
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

        ];

        if (request()->email != $user->email) {
            $data['email'] = 'required|email:rfc,dns,strict,spoof,filter|unique:users';

        }

        if (Auth::user()->is_admin) {
            if (request()->number != $user->number) {
                $data['number'] = 'required|string|unique:users|phone';
            }
            $data['password'] = 'nullable|min:5|string|confirmed';

        } else {
            $data['password'] = 'nullable|min:5|string|confirmed|required_with:old_password';
            $data['old_password'] = ['required', 'string', new checkOldPassword($user)];

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
        $this->authorize('upgrade', $user);
        // if (!request()->user->lastSub()) {
        //     abort(503);
        // }
        //return $user->lastSub();

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

            $user->giveReferralBounus($amount, 'Subscription bonus');

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

        try {

            $user->notify(new alert($desc));

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
        $type = request()->type ? request()->type : '';

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

        }); //->get();

        if (request()->wantsJson()) {
            //return $q->get();
            return TransactionResource::collection($q->get());
        }

        $pagination = $q->paginate(100);

        $transactions = $pagination->sortByDesc('created_at');

        $totalDebit = $user->transactions->where('type', 'debit');
        $totalCredit = $user->transactions->where('type', 'credit');

        $q = $q->get();

        $credit = $q->where('type', 'credit'); //->get();

        $debit = $q->where('type', 'debit'); //->get();

        $reasons = $user->transactions->pluck('reason')->unique();
        $types = $user->transactions->pluck('type')->unique();

        //$transactions = $query->paginate(100);
        //$transactions->paginate(1);
        //return $transactions;

        $compact = compact('transactions', 'pagination', 'credit', 'debit', 'from', 'to', 'reason', 'reasons', 'ref', 'totalCredit', 'totalDebit', 'type', 'types');

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

        });

        $pagination = $query->paginate(100);

        $transactions = $pagination->sortByDesc('created_at');

        //$referrals = $Referral::get();

        $compact = compact('transactions', 'pagination', 'from', 'to', 'ref');

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

            $user->notify(new alert($desc));

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

            $u->notify(new alert($descTo));

        } catch (\Throwable $th) {
            //throw $th;
        }

        $descFrom = "Transfer of {$amount} to {$u->login}";

        $tranTo = Transaction::create([
            'amount' => $amount,
            'balance' => $user->balance,
            'type' => 'debit',
            'desc' => "{$descFrom}",
            'ref' => generateRef($user),
            'user_id' => $user->id,
            'reason' => 'transfer',
        ]);

        $activityTo = Activity::create([
            'user_id' => $user->id,
            'admin_id' => auth()->user()->id,
            'summary' => $descFrom,
        ]);

        try {

            $u->notify(new alert($descFrom));

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
            'desc' => "required|string|max:100",
            //'password' => ["required", new checkOldPassword(Auth::user())],
        ]);

        $amount = request()->amount;
        $desc = request()->desc;
        $fullDesc = "{$amount} debited from wallet. Description: " . $desc;

        $user->update([
            'balance' => $user->balance - $amount,
        ]);

        $tran = Transaction::create([
            'amount' => $amount,
            'balance' => $user->balance,
            'type' => 'debit',
            'desc' => "{$desc}",
            'ref' => generateRef($user),
            'user_id' => $user->id,
            'reason' => 'debit',
        ]);

        $activity = Activity::create([
            'user_id' => $user->id,
            'admin_id' => auth()->user()->id,
            'summary' => $desc,
        ]);

        try {

            $user->notify(new alert($fullDesc));

        } catch (\Throwable $th) {
            //throw $th;
        }
        return $this->jsonWebBack('success', $fullDesc/* , $user->routePath() */);

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

        $this->giveReferralBonus($user);

        try {

            $user->notify(new alert($desc));

        } catch (\Throwable $th) {
            //throw $th;
        }
        return $this->jsonWebBack('success', "{$amount} added to wallet"/* , $user->routePath() */);

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
        unset($networks['mtn_sme']);
        //unset($networks['mtn_direct']);

        $bills = config("settings.bills.airtime");

        //return $bills;
        $data = [
            'network' => "required|string|in:" . implode(',', array_keys($networks)),
        ];

        if (!request()->wantsJson()) {
            $data['password'] = ["required", new checkOldPassword($user)];
        }

        $this->validate(request(), $data);

        $network = request()->network;
        $network_code = $networks[$network];

        request()->merge(['network' => strtolower(request()->network)]);
        $this->validate(request(), [
            //'network' => "required|string|in:" . implode(',', array_keys($networks)),
            'amount' => "required|numeric|min:{$bills[$network]['min']}|max:{$bills[$network]['max']}",
            'number' => "required|string|phone",

            //'discount_amount' => ["required", "numeric", new checkBalance($user)],
        ]);

        $discount_amount = calDiscountAmount(request()->amount, airtimeDiscount($user)[$network]);
        
        $number = nigeriaNumber(request()->number);
        //return $this->isDublicate($user, $discount_amount, 'airtime');
        $desc = "Recharge of " . strtoupper($network) . " " . currencyFormat(request()->amount) . " to " . $number;

        if ($this->isDublicate($user, $discount_amount, $desc, 'airtime')) {
            return $this->jsonWebBack('error', dublicateMessage());
        }

        request()->merge(['discount_amount' => $discount_amount]);
        $this->validate(request(), [
            'discount_amount' => [new checkBalance($user)],
        ]);

        $ref = generateRef($user);

        /*  if (!env('ENABLE_BILL_PAYMENT')) {
        return env('ERROR_MESSAGE') ? $this->jsonWebBack('error', env('ERROR_MESSAGE')) : $this->jsonWebBack('success', $desc, $ref);
        } */

        //return $number;

        $result = $this->airtime(request()->amount, $number, $network_code, $ref);

        //return $result;

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

        $networks = config("settings.mobile_networks");
        //unset($networks['mtn']);
        //unset($networks['mtn_direct']);
        $bills = config("settings.bills.data");

//return request()->network_code;
        $data = [
            'network' => "required|string|in:" . implode(',', array_keys($networks)),
            'number' => "required|string|phone",
            'amount' => "required|numeric",
        ];

        if (!request()->wantsJson()) {
            $data['password'] = ["required", new checkOldPassword($user)];
        }

        $this->validate(request(), $data);

        //return request()->amount;

        $network = request()->network;
        $network_code = $networks[$network];
        $planKey = array_search(request()->amount, array_column($bills[$network], 'data_amount'));
        $plan = $bills[$network][$planKey];
        $price = $plan['price'];
        $formatPrice = currencyFormat($price);

        $details = getLastString($plan["id"]) . " - {$formatPrice} - {$plan['validity']}";

        //return $plan;

        $discount_amount = calDiscountAmount($price, dataDiscount($user)[$network]);
        $desc = "Data subscription of " . strtoupper($network) . " " . $details . " to " . request()->number;

        //return $desc;

        if ($this->isDublicate($user, $discount_amount, $desc, 'data')) {
            return $this->jsonWebBack('error', dublicateMessage());
        }

        request()->merge(['discount_amount' => $discount_amount]);
        $this->validate(request(), [
            'discount_amount' => ["required", "numeric", new checkBalance($user)],
        ]);

        $ref = generateRef($user);

        /* if (!env('ENABLE_BILL_PAYMENT')) {
        return env('ERROR_MESSAGE') ? $this->jsonWebBack('error', env('ERROR_MESSAGE')) : $this->jsonWebBack('success', $desc, $ref);
        } */

        $number = nigeriaNumber(request()->number);

        if ($network == 'mtn_sme') {
            $result = $this->dataMtn(request()->amount, $number, $network_code, $ref);

        } else {

            $result = $this->data(request()->amount, $number, $network_code, $ref);
        }

        //return $result;

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
            'number' => "nullable|string|phone",
            'customer_name' => "required_unless:type,startimes",
            'customer_number' => "required_unless:type,startimes",

        ];

        if (!request()->wantsJson()) {
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
        $desc = "Cable Subscription of {$details} for smart no {$smart_no}";
        //return $discount_amount;

        if ($this->isDublicate($user, $discount_amount, $desc, 'cable')) {
            return $this->jsonWebBack('error', dublicateMessage());
        }

        request()->merge(['discount_amount' => $discount_amount]);
        $this->validate(request(), [
            'discount_amount' => [new checkBalance($user)],
        ]);

        //$number = request()->number;

        $ref = generateRef($user);

        /* if (!env('ENABLE_BILL_PAYMENT')) {
        return env('ERROR_MESSAGE') ? $this->jsonWebBack('error', env('ERROR_MESSAGE')) : $this->jsonWebBack('success', $desc, $ref);
        } */

        $number = request()->number ? nigeriaNumber(request()->number) : $user->nigeria_number;

        if ($type == 'startimes') {
            $result = $this->startimeCable(request()->amount, $smart_no, $number);

        } else {
            $result = $this->cable($type, request()->amount, $smart_no, request()->customer_name, request()->customer_number, request()->invoice, $number);
        }

        // return $result;

        if (is_array($result) && isset($result['error'])) {
            return $this->jsonWebBack('error', $result['error']);
        }

        /* if (!isset($result['exchangeReference'])) {
        return $this->jsonWebBack('error', 'An error ocurred');
        } */

        $ref = $result['exchangeReference'] ?? $ref;

        return $this->saveTransaction($user, 'cable', $discount_amount, $desc, $ref, $result);

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
        $this->authorize('delete', $user);

        $user->update(['is_active' => !$user->is_active]);

        try {

            $user->notify(new alert("Your Account is {$user->status()}", false));

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

}