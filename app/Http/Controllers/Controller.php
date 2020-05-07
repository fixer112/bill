<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Jobs\SendEmail;
use App\Mail\lowBalance;
use App\Mail\massMail;
use App\Notifications\alert;
use App\Traits\BillPayment;
use App\Traits\Payment;
use App\Transaction;
use App\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use BillPayment, Payment;

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, BillPayment;

    public function jsonWebBack($type, $message)
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

        $result = $this->airtime($amount, $tranx->data->metadata->number, $tranx->data->metadata->network_code, $ref);

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

        if ($tranx->data->metadata->network == 'mtn_sme') {

            $result = $this->dataMtn($tranx->data->metadata->amount, $tranx->data->metadata->number, $tranx->data->metadata->network_code, $ref);

        } else {

            $result = $this->data($tranx->data->metadata->amount, $tranx->data->metadata->number, $tranx->data->metadata->network_code, $ref);
        }

        //return $result;

        if (is_array($result) && isset($result['error'])) {
            return $this->jsonWebBack('error', $result['error']);
        }

        $desc = "Data subscription of " . strtoupper($tranx->data->metadata->network) . " " . $tranx->data->metadata->details . " to " . $tranx->data->metadata->number;

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

        return $this->jsonWebRedirect('success', $desc, $user->routePath(), $ref);

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

    public function test()
    {

        //return $this->balance();
        //return $this->cableInfo('dstv', '7036717423');
        // return getCable()['startime'];
        //return new bulkMail('Test', '<b>Testing</b> This is a test');
        return fetchDataInfo();
        return $this->fetchDataInfo('airtel');
        return $this->airtime(50, '08106813749', '77777', generateRef());
    }
}