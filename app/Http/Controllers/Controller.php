<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Http\Resources\User as UserResource;
use App\Jobs\SendEmail;
use App\Mail\bulkMail;
use App\Mail\lowBalance;
use App\Mail\massMail;
use App\Notifications\alert;
use App\SmsHistory;
use App\Traits\BillPayment;
use App\Traits\Main;
use App\Traits\MoniWalletBill;
use App\Traits\Notify;
use App\Traits\Payment;
use App\Transaction;
use App\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use KingFlamez\Rave\Facades\Rave;
use Throwable;

class Controller extends BaseController
{
    use BillPayment, Payment, Main, Notify;

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function jsonWebBack($type, $message, $ref = null)
    {
        if (request()->wantsJson()) {
            $data = [$type => $message];
            if ($ref) {
                $data['reference'] = $ref;
            }
            return $data;

        }
        request()->session()->flash($type, $message);

        //return $message;
        return url()->previous() != url()->current() || request()->isMethod('post') /*  && !request()->isMethod('post') */ ? back() : $message;

    }
    public function jsonWebRedirect($type, $message, $link, $ref = null)
    {
        if (request()->wantsJson()) {
            $data = [$type => $message];
            if ($ref) {
                $data['reference'] = $ref;
            }
            return $data;
        }
        request()->session()->flash($type, $message);

        return redirect($link);
    }

    public function guestAirtime($reference)
    {
        //return;
        //return $this->jsonWebBack('error', 'Online Payment Currently Disabled');

        $tranx = $this->validateGuestPayment($reference, 'airtime');
        // return \json_encode($tranx);

        if ( /* is_array($tranx) &&  */isset($tranx['error'])) {
            return $this->jsonWebBack('error', $tranx['error']);
        }

        $amount = getRaveMetaValue($tranx['data']['meta'], 'amount'); //removeCharges(($tranx->data->amount / 100), $tranx->data->metadata->amount);

        $ref = generateRef();
        $number = nigeriaNumber(getRaveMetaValue($tranx['data']['meta'], 'number'));

        /* if (!env('ENABLE_BILL_PAYMENT')) {
        return env('ERROR_MESSAGE') ? $this->jsonWebBack('error', env('ERROR_MESSAGE')) : $this->jsonWebBack('success', $desc, $ref);
        }

        if (getRaveMetaValue($tranx['data']['meta'], 'network') == 'mtn_sns') {
        $result = $this->mtnAirtime($amount, $number, $ref);

        } else {

        $result = $this->airtime($amount, $number, getRaveMetaValue($tranx['data']['meta'], 'network_code'), $ref);
        } */

        //return $result;
        $result = [];

        if (is_array($result) && isset($result['error'])) {
            return $this->jsonWebBack('error', $result['error']);
        }

        $desc = "Recharge of " . strtoupper(getRaveMetaValue($tranx['data']['meta'], 'network')) . " " . currencyFormat($amount) . " to " . $number;

        $tran = Transaction::create([
            'amount' => $amount,
            'desc' => "{$desc}",
            'ref' => $reference,
            'reason' => 'airtime',
            'balance' => 0,
        ]);

        return $this->jsonWebBack('success', $tran->desc);
    }

    public function guestData($reference)
    {
        //return;
        //return $this->jsonWebBack('error', 'Online Payment Currently Disabled');

        $tranx = $this->validateGuestPayment($reference, 'data');
        // return \json_encode($tranx);

        if ( /* is_array($tranx) && */isset($tranx['error'])) {
            return $this->jsonWebBack('error', $tranx['error']);
        }

        //return json_decode(json_encode($tranx), true);

        $amount = getRaveMetaValue($tranx['data']['meta'], 'amount'); //removeCharges(($tranx->data->amount / 100), $tranx->data->metadata->amount);

        $ref = generateRef();

        $number = nigeriaNumber(getRaveMetaValue($tranx['data']['meta'], 'number'));
        /* if (!env('ENABLE_BILL_PAYMENT')) {
        return env('ERROR_MESSAGE') ? $this->jsonWebBack('error', env('ERROR_MESSAGE')) : $this->jsonWebBack('success', $desc, $ref);
        }

        if (getRaveMetaValue($tranx['data']['meta'], 'network') == 'mtn_sme') {

        $result = $this->dataMtn(getRaveMetaValue($tranx['data']['meta'], 'price'), $number, getRaveMetaValue($tranx['data']['meta'], 'network_code'), $ref);

        } else {

        $result = $this->data(getRaveMetaValue($tranx['data']['meta'], 'price'), $number, getRaveMetaValue($tranx['data']['meta'], 'network_code'), $ref);
        } */

        //return $result;
        $result = [];

        if (is_array($result) && isset($result['error'])) {
            return $this->jsonWebBack('error', $result['error']);
        }

        $desc = "Data subscription of " . strtoupper(getRaveMetaValue($tranx['data']['meta'], 'network')) . " " . getRaveMetaValue($tranx['data']['meta'], 'details') . " to " . $number;

        $tran = Transaction::create([
            'amount' => $amount,
            'desc' => "{$desc}",
            'ref' => $reference,
            'reason' => 'data',
            'balance' => 0,
        ]);

        return $this->jsonWebBack('success', $tran->desc);

    }

    public function verifySmartCard($type, $number)
    {

        return $this->cableInfo($type, $number);

    }
    public function verifyMeter($service, $number)
    {
        return $this->electricityInfo($service, $number);

    }

    public function ussdHook()
    {

        Log::debug("All request: " . json_encode(request()->all()));

        $this->validate(request(), [
            'refid' => 'required|string|exists:transactions,ref',
        ]);

        $tran = Transaction::where('ref', request()->refid)->first();

        if ($tran == 'failed') {
            return $tran;
        }

        //Log::debug($tran);

        $result = MoniWalletBill::checkUssdStatus($tran->ref);

        //Log::debug($result);

        if (is_array($result) && isset($result['error'])) {
            return errorMessage($result['error']);

        }

        $status = 'pending';

        if ($result['data']['code'] == 2) {
            $status = 'approved';
            try {

                $user->notify(new alert($tran->desc, $tran));

            } catch (\Throwable $th) {
                //throw $th;
            }

        }

        if ($result['data']['code'] == 3) {
            $status = 'failed';

            $top = Transaction::create([
                'amount' => $tran->amount,
                'balance' => $tran->user->balance + $tran->amount,
                'type' => 'credit',
                'desc' => "Reverse transaction $tran->ref",
                'ref' => generateRef($tran->user),
                'user_id' => $tran->user->id,
                'reason' => 'top-up',
                'plathform' => getPlathform(),
            ]);

            $tran->user->update([
                'balance' => $tran->user->balance + $tran->amount,
            ]);
        }

        $tran->update([
            'status' => $status,
        ]);

        //Log::debug($tran);

        if (request()->redirect == 'true') {
            return back();
        }
        return $tran;

    }

    public function saveTransaction(User $user, $type, $discount_amount, $desc, $ref, $result, $profit = 0, $ussd = false)
    {
        if (is_array($result) && isset($result['error'])) {
            return $this->jsonWebRedirect('error', $result['error'], "user/{$user->id}/$type");
        }

        $user->update([
            'balance' => $user->balance - $discount_amount,
        ]);

        $tran = Transaction::create([
            'amount' => $discount_amount,
            'balance' => $user->balance,
            'type' => 'debit',
            'desc' => "{$desc}",
            'ref' => $ref,
            'user_id' => $user->id,
            'reason' => $type,
            'plathform' => getPlathform(),
            'profit' => $profit,
        ]);

        if ($ussd) {
            $tran->update([
                'status' => 'pending',
                'ussd_id' => $result['log_id'],
                'desc' => "$tran->desc (ussd)",
            ]);
        }

        if ($type == 'electricity') {
            $tran->update([
                'desc' => "$tran->desc pin {$result['pincode']}",
            ]);
        }

        if ($type == 'sms') {
            SmsHistory::create([
                'sms_group_id' => request()->group,
                'sender' => request()->sender,
                'message' => request()->message,
                'numbers' => $result['all_numbers'],
                'success_numbers' => $result['successful'],
                'failed_numbers' => "${result['failed']},{$result['insufficient_unit']}",
                'invalid_numbers' => $result['invalid'],
                'transaction_id' => $tran->id,
                'nondnd_numbers' => $result['nondnd_numbers'],
                'dnd_numbers' => $result['dnd_numbers'],
                'units' => $result['units_used'],

            ]);

        }

        $activity = Activity::create([
            'user_id' => $user->id,
            'admin_id' => auth()->user()->id,
            'summary' => $desc,
        ]);

        try {
            if (!$ussd) {

                $user->notify(new alert($desc, $tran));
            }

        } catch (\Throwable $th) {
            //throw $th;
        }

        if (request()->wantsJson()) {
            return $tran;
        }
        //return "user/{$user->id}/$type";
        return $this->jsonWebRedirect('success', $desc, "user/{$user->id}/$type", $ref);

    }

    public function refreshData()
    {
        Storage::delete('data.json');
        getDataInfo();
        return json_decode(Storage::get('data.json'), true)['data'];

    }
    public function refreshElectricity()
    {
        Storage::delete('electricity.json');
        getElectricityInfo();
        return json_decode(Storage::get('electricity.json'), true)['electricity'];

    }

    public function testUser(User $user)
    {
        /* return (new UserCreated())
        ->toMail($user->email); */
        //return (new lowBalance(12));

        //return Mail::to('support@moniwallet.com')->queue(new bulkMail('<b>test</b>'));
        //return;
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        return;

        $details = ['email' => 'gmail.com'];
        return SendEmail::dispatch($details);

        return new massMail($user, '<b>test</b>');

        return Mail::to('support@moniwallet.com')->send(new lowBalance(10));

        $user->notify(new alert("Your Account is suspended"));
        //$user->notify(new UserCreated());

        return $user->email;

        return calPercentageAmount(100, 200); //calDiscountAmount(10, $user->calDiscount());
    }

    public function auth()
    {
        $response = Http::withHeaders([
            "Authorization" => "Basic " . base64_encode(env('MONIFY_KEY') . ':' . env('MONIFY_SECRET')),
        ])->post(env('MONIFY_URL') . "/api/v1/auth/login");

        return $response['responseBody']['accessToken'];

    }

    public function reserveAccount(User $user)
    {
        $authkey = $this->auth();

        $data = [
            "accountName" => $user->full_name,
            "accountReference" => $user->login,
            "currencyCode" => "NGN",
            "contractCode" => env('MONIFY_CODE'),
            "customerName" => $user->full_name,
            "customerEmail" => $user->email,
        ];

        $response = Http::withHeaders([
            'Content-Type' => "application/json",

        ])
            ->withToken($authkey)
            ->get(env('MONIFY_URL') . "/api/v1/bank-transfer/reserved-accounts/{$user->login}");

        if ($response['requestSuccessful'] == true) {
            return $this->updateUser($user, $response['responseBody']);
            return $response['responseBody'];
        }

        $response = Http::withHeaders([
            'Content-Type' => "application/json",

        ])
            ->withToken($authkey)
            ->post(env('MONIFY_URL') . '/api/v1/bank-transfer/reserved-accounts', $data);

        //return $response;
        return $this->updateUser($user, $response['responseBody']);

        return $response['responseBody'];
    }

    public function monifyTransfer()
    {
        //return request()->accountDetails['accountName'];

        $this->validate(request(), [
            "transactionReference" => 'required|string|unique:transactions,ref',
            "paymentReference" => 'required|string',
            "amountPaid" => 'required|numeric',
            "totalPayable" => 'required|numeric',
            "paidOn" => 'required|string',
            "paymentStatus" => 'required|string',
            //"accountReference" => 'required|string|exists:users,login',
            "paymentDescription" => 'required|string',
            "transactionHash" => 'required|string',
            "accountDetails.accountName" => 'required|string',
            "accountDetails.bankCode" => 'required|string',
            "accountDetails.accountNumber" => 'required|string',
            "product.reference" => 'required|string|exists:users,login',
        ]);

        $secret = env("MONIFY_SECRET");
        $pref = request()->paymentReference;
        $tref = request()->transactionReference;
        $amountPaid = request()->amountPaid;
        $paidOn = request()->paidOn;

        /* $password = "$secret|$pref|$amountPaid|$paidOn|$tref";
        $verify = hash('sha512', $password);
        //return Hash::check($password, request()->transactionHash);
        // return $verify == request()->transactionHash;
        $verify = password_verify($verify, request()->transactionHash);
        return var_dump($verify); */
        //return;
        $verify = $this->verifyTransfer(request()->transactionReference);
        //return $verify;
        if (!$verify || !$verify['requestSuccessful'] || $verify['responseBody']['paymentStatus'] != "PAID") {
            return ['error' => 'Transaction not available'];
        }

        //return $verify;

        $body = $verify['responseBody'];
        $user = User::where('login', request()->product['reference'])->first();
        $paymentDescription = request()->paymentDescription;
        $bankName = request()->accountDetails['accountName'];
        $bankCode = request()->accountDetails['bankCode'];
        $accNumber = request()->accountDetails['accountNumber'];
        $ref = request()->transactionReference;

        return $this->saveMonify($user, $ref, $body['amount'], $paymentDescription, $bankName, $bankCode, $accNumber);

    }

    public function saveMonify(User $user, $ref, $amount, $paymentDescription, $bankName = "", $bankCode = "", $accNumber = "")
    {
        $charges = (env("MONIFY_FEE", 2) / 100) * $amount;
        $fee = (0.75 / 100) * $amount;
        $vat = (7.5 / 100) * $fee;
        $profit = $charges - ($fee + $vat);
//$charges = $charges > env("MONIFY_CAP", 250) ? env("MONIFY_CAP", 250) : $charges;
        $amount = $amount - $charges;
        $balance = $user->balance + $amount;

        Main::fundBonus($user, $amount);

        $user->update(['balance' => $balance]);

        $currencyAmount = currencyFormat($amount);

        $desc = "Wallet funding of {$currencyAmount} by Transfer ({$paymentDescription})";

        $transaction = Transaction::create([
            'amount' => $amount,
            'balance' => $user->balance,
            'type' => 'credit',
            'desc' => $desc,
            'ref' => $ref,
            'user_id' => $user->id,
            'is_online' => 0,
            'bank_name' => $bankName,
            'bank_code' => $bankCode,
            'bank_acc' => $accNumber,
            'profit' => $profit,
            //'reason' => 'top-up',
        ]);

        $activity = Activity::create([
            'user_id' => $user->id,
            'admin_id' => 1,
            'summary' => $desc,
        ]);

        $this->giveReferralBonus($user);

        try {

            $user->notify(new alert($desc, $transaction));

        } catch (\Throwable $th) {
            //throw $th;
        }

        return $transaction;

    }

    public function monifySearch()
    {
        $authkey = $this->auth();

        $query = [];

        $query['paymentStatus'] = request()->status ?? 'PAID';

        $query['size'] = request()->size ?? '20';

        if (request()->ref) {
            $query['transactionReference'] = request()->ref;
        }

        $response = Http::withHeaders([
            'Content-Type' => "application/json",

        ])
            ->withToken($authkey)
            ->get(env('MONIFY_URL') . "/api/v1/transactions/search?" . http_build_query($query));

        if (!isset($response['requestSuccessful']) || !$response['requestSuccessful']) {
            return $response;
        }

        //return $response;

        $transactions = [];

        foreach ($response['responseBody']['content'] as $payment) {
            $user = User::where('email', $payment['customerDTO']['email'])->first();
            $ref = $payment['transactionReference'];
            $exist = Transaction::where('ref', $ref)->first();
            if (!$user || $exist) {
                continue;
            }
            $amount = $payment['amount'];
            $paymentDescription = $payment['paymentDescription'];
            array_push($transactions, $this->saveMonify($user, $ref, $amount, $paymentDescription));
        }
        return $transactions;
        return $response['responseBody']['content'];

    }

    public function verifyTransfer($ref)
    {
        //$authkey = $this->auth();

        $response = Http::withHeaders([
            'Content-Type' => "application/json",
            "Authorization" => "Basic " . base64_encode(env('MONIFY_KEY') . ':' . env('MONIFY_SECRET')),

        ])
            ->get(env('MONIFY_URL') . "/api/v1/merchant/transactions/query/?transactionReference={$ref}");

        return $response;

    }

    public function updateUser(User $user, $reserved)
    {
        if ($user->account_number == '' || $user->account_reference == '' || $user->bank_name) {
            $user->update([
                'account_number' => $reserved['accountNumber'],
                'account_reference' => $reserved['reservationReference'],
                'bank_name' => $reserved['bankName'],
            ]);
        }
        return $user;
    }

    public function updateUsers($id = 0)
    {
        $users = User::where('is_admin', 0)->where('id', '>=', $id)->get();

        $collecteds = [];

        $users->each(function ($user) use (&$collecteds) {
            if ($user->ref == '' || $user->ref_reserved == '') {
                $reserved = $this->reserveAccount($user);
                /* $user->update([
                'account_number' => $reserved['accountNumber'],
                'account_reference' => $reserved['reservationReference'],
                'bank_name' => $reserved['bankName'],
                ]); */

                array_push($collecteds, $user);

            }
        });

        return [$users->last()->id, $collecteds];

    }

    public function initializeRave()
    {
        //This initializes payment and redirects to the payment gateway
        //The initialize method takes the parameter of the redirect URL
        //return route('callback');
        Rave::initialize(route('callback'));
    }

    public function suspendID(User $user)
    {
        $userIDs = explode(',', env('SUSPEND_USERS'));

        if (in_array($user->id, $userIDs)) {
            $user->update(['is_active' => 0]);

        }
    }
    public function test()
    {
        return fetchDataInfo();
        return BillPayment::fetchDataInfo('etisalat');

        return $this->fetchDataInfo(request()->type ?? 'glo');
        $this->dataMtn('', '08106813749', '15', generateRef());

        return generateRef(User::find(35));

        return fetchDataInfo();
        return $this->balance();

        //return explode(',',env(''));
        return MoniWalletBill::mtnSNS('08106813749', "100", generateRef());
        return formatPhoneNumberArray('dwdmwdg,676bgggh,08106813749');
        return MoniWalletBill::sms('749', 'This is a test', 3);
        return fetchElectricityInfo();

        return $this->sms('', '08106813749');
        return config("settings.bills.electricity");
        return getElectricityInfo();
        return $this->app(User::find(2), 'This is a test', 'test');
        return new UserResource(User::find(2));

        $data['general_alert'] = env("GENERAL_ALERT");
        return $data;

        //return $this->sms("This is a test from moniwallet,the sender name is the issue here.", '09049941820', 'MoniWallet');
        return $this->cableInfo('dstv', '7036717423');

        return $this->mtnAirtime(50, '', 'mtntest');

        return $this->reserveAccount(User::find(2));
        return $this->verifyTransfer("MNFY|20200512181838|000258");

        //return $this->balance();
        // return getCable()['startime'];
        return new bulkMail('Test', '<b>Testing</b> This is a test');
        return $this->airtime(50, '08106813749', '77777', generateRef());
    }
}