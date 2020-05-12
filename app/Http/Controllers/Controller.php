<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Jobs\SendEmail;
use App\Mail\bulkMail;
use App\Mail\lowBalance;
use App\Mail\massMail;
use App\Notifications\alert;
use App\Traits\BillPayment;
use App\Traits\Main;
use App\Traits\Payment;
use App\Transaction;
use App\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use BillPayment, Payment, Main;

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, BillPayment;

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
        return url()->previous() == url()->current() && !request()->isMethod('post') ? $message : back();

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
        $tranx = $this->validateGuestPayment($reference, 'airtime');
        // return \json_encode($tranx);

        if (is_array($tranx) && isset($tranx['error'])) {
            return $this->jsonWebBack('error', $tranx['error']);
        }

        $amount = removeCharges(($tranx->data->amount / 100), $tranx->data->metadata->amount);

        $ref = generateRef();

        if (!env('ENABLE_BILL_PAYMENT')) {
            return env('ERROR_MESSAGE') ? $this->jsonWebBack('error', env('ERROR_MESSAGE')) : $this->jsonWebBack('success', $desc, $ref);
        }

        $number = nigeriaNumber($tranx->data->metadata->number);

        $result = $this->airtime($amount, $number, $tranx->data->metadata->network_code, $ref);

        //return $result;

        if (is_array($result) && isset($result['error'])) {
            return $this->jsonWebBack('error', $result['error']);
        }

        $desc = "Recharge of " . strtoupper($tranx->data->metadata->network) . " " . currencyFormat($amount) . " to " . $tranx->data->metadata->number;

        $tran = Transaction::create([
            'amount' => $amount,
            'desc' => "{$desc}",
            'ref' => $tranx->data->reference,
            'reason' => 'airtime',
            'balance' => 0,
        ]);

        return $this->jsonWebBack('success', $tran->desc);
    }

    public function guestData($reference)
    {

        $tranx = $this->validateGuestPayment($reference, 'data');
        // return \json_encode($tranx);

        if (is_array($tranx) && isset($tranx['error'])) {
            return $this->jsonWebBack('error', $tranx['error']);
        }

        //return json_decode(json_encode($tranx), true);

        $amount = removeCharges(($tranx->data->amount / 100), $tranx->data->metadata->amount);

        $ref = generateRef();

        if (!env('ENABLE_BILL_PAYMENT')) {
            return env('ERROR_MESSAGE') ? $this->jsonWebBack('error', env('ERROR_MESSAGE')) : $this->jsonWebBack('success', $desc, $ref);
        }

        $number = nigeriaNumber($tranx->data->metadata->number);

        if ($tranx->data->metadata->network == 'mtn_sme') {

            $result = $this->dataMtn($tranx->data->metadata->amount, $number, $tranx->data->metadata->network_code, $ref);

        } else {

            $result = $this->data($tranx->data->metadata->amount, $number, $tranx->data->metadata->network_code, $ref);
        }

        //return $result;

        if (is_array($result) && isset($result['error'])) {
            return $this->jsonWebBack('error', $result['error']);
        }

        $desc = "Data subscription of " . strtoupper($tranx->data->metadata->network) . " " . $tranx->data->metadata->details . " to " . $number;

        $tran = Transaction::create([
            'amount' => $amount,
            'desc' => "{$desc}",
            'ref' => $tranx->data->reference,
            'reason' => 'data',
            'balance' => 0,
        ]);

        return $this->jsonWebBack('success', $tran->desc);

    }

    public function verifySmartCard($type, $number)
    {
        $number = nigeriaNumber($number);

        return $this->cableInfo($type, $number);

    }

    public function saveTransaction(User $user, $type, $discount_amount, $desc, $ref, $result)
    {
        if (is_array($result) && isset($result['error'])) {
            return $this->jsonWebBack('error', $result['error']);
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
            'plathform' => request()->wantsJson() ? 'api' : 'web',
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

        if (request()->wantsJson()) {
            return $tran;
        }

        return $this->jsonWebBack('success', $desc/* ,$user->routePath() */, $ref);

    }

    public function refreshData()
    {
        Storage::delete('data.json');
        getDataInfo();
        return json_decode(Storage::get('data.json'), true)['data'];

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

        $user->notify(new alert("Your Account is suspended", false));
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

        $verify = $this->verifyTransfer(request()->transactionReference);
        //return $verify;
        if (!$verify || !$verify['requestSuccessful'] || $verify['responseBody']['paymentStatus'] != "PAID") {
            return ['error' => 'Transaction not available'];
        }

        $body = $verify['responseBody'];
        $user = User::where('login', request()->product['reference'])->first();
        $charges = (env("MONIFY_FEE", 0.5) / 100) * $body['amount'];
        $charges = $charges > env("MONIFY_CAP", 250) ? env("MONIFY_CAP", 250) : $charges;
        $amount = $body['amount'] - $charges;
        $balance = $user->balance + $amount;
        $user->update(['balance' => $balance]);
        $paymentDescription = request()->paymentDescription;

        $desc = "Wallet funding by Transfer ({$body['paymentDescription']})";

        $transaction = Transaction::create([
            'amount' => $amount,
            'balance' => $user->balance,
            'type' => 'credit',
            'desc' => $desc,
            'ref' => request()->transactionReference,
            'user_id' => $user->id,
            'is_online' => 0,
            'bank_name' => request()->accountDetails['accountName'],
            'bank_code' => request()->accountDetails['bankCode'],
            'bank_acc' => request()->accountDetails['accountNumber'],
            //'reason' => 'top-up',
        ]);

        $activity = Activity::create([
            'user_id' => $user->id,
            'admin_id' => 1,
            'summary' => $desc,
        ]);

        $this->giveReferralBonus($user);

        try {

            $user->notify(new alert($desc));

        } catch (\Throwable $th) {
            //throw $th;
        }

        return $transaction;
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

    public function updateUsers($id)
    {
        $users = User::where('is_admin', 0)->where('id', '>=', $id)->get();

        $correcteds = [];

        $users->each(function ($user) use ($collecteds) {
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

        return [$users->last()->id, $correcteds];

    }

    public function hook()
    {

    }

    public function test()
    {

        return $this->reserveAccount(User::find(3));
        return $this->sms('This is a test', '3567u65', 'MoniWallet');
        return $this->verifyTransfer("MNFY|20200512181838|000258");

        //return $this->balance();
        //return $this->cableInfo('dstv', '7036717423');
        // return getCable()['startime'];
        return fetchDataInfo();
        return new bulkMail('Test', '<b>Testing</b> This is a test');
        return $this->fetchDataInfo('airtel');
        return $this->airtime(50, '08106813749', '77777', generateRef());
    }
}