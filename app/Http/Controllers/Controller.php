<?php

namespace App\Http\Controllers;

use App\Notifications\alert;
use App\Traits\BillPayment;
use App\Traits\Payment;
use App\Transaction;
use App\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

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

        $amount = removeCharges(($tranx->data->amount / 100), $tranx->data->metadata->amount);

        $ref = generateRef();

        if ($tranx->data->metadata->network == 'mtn') {

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

    public function testUser(User $user)
    {
        /* return (new UserCreated())
        ->toMail($user->email); */

        $user->notify(new alert("Your Account is suspended", false));
        //$user->notify(new UserCreated());

        return $user->email;

        return calPercentageAmount(100, 200); //calDiscountAmount(10, $user->calDiscount());
    }

    public function test()
    {
        //return generateRef();
        /*  throw new Exception('Error Testing');
        try {

        throw new Exception('Error Testing');

        } catch (Exception $e) {

        }
        return; */

        //return fetchDataInfo();
        //return $this->data2();
        //return $this->fetchDataInfo('glo');
        return $this->balance();
        return $this->airtime(50, '08106813749', '77777', generateRef());
    }
}