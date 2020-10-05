<?php
namespace App\Traits;

use App\Transaction;
use App\User;
use Illuminate\Support\Facades\Http;
/**
 *
 */
trait Payment
{
    public static function validateReference($reference)
    {
        $data = array('txref' => $reference,
            'SECKEY' => env("RAVE_SECRET_KEY"), //secret key from pay button generated on rave dashboard
        );

        $tranx = Http::post("https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify", $data)->throw();

        return $tranx;

    }
    public static function validatePayment($reference, $reason)
    {

        if (!$reference) {
            return ['error' => 'No reference supplied'];
        }

        //$paystack = new Paystack(env('PAYSTACK_SECRET'));
        try
        {
            // verify using the library
            /*  $tranx = $paystack->transaction->verify([
            'reference' => $reference, // unique to transactions
            ]); */
            $tranx = self::validateReference($reference);
            //return $tranx;

        } catch (\Throwable $e) {

            /* \Paystack\Exception\ApiException $e */
            // print_r($e->getResponseObject());
            return ['error' => $e->getMessage()];
        }

        if ('success' != $tranx['status'] || '00' != $tranx['data']['chargecode']) {
            return ['error' => "Payment {$reference} failed, pls try again"];

        }

        if (!$user = User::find(getRaveMetaValue($tranx['data']['meta'], 'user_id')) || $user->is_admin) {
            return ['error' => 'User does not exist'];

        }

        if (getRaveMetaValue($tranx['data']['meta'], 'reason') != $reason) {

            return ['error' => "Payment is not for {$reason}"];

        }

        if (Transaction::where('ref', $reference)->first()) {
            return ['error' => "Payment {$reference} already approved"];

        }

        return $tranx;

    }

    public static function validateGuestPayment($reference, $reason)
    {

        if (!$reference) {
            return ['error' => 'No reference supplied'];
        }

// initiate the Library's Paystack Object
        //$paystack = new Paystack(env('PAYSTACK_SECRET'));
        try
        {
            // verify using the library
            /* $tranx = $paystack->transaction->verify([
            'reference' => $reference, // unique to transactions
            ]); */
            $tranx = self::validateReference($reference);

        } catch (\Throwable $e) {

            /* \Paystack\Exception\ApiException $e */
            // print_r($e->getResponseObject());
            return ['error' => $e->getMessage()];
        }
        if ('success' != $tranx['status'] || '00' != $tranx['data']['chargecode']) {
            return ['error' => "Payment {$reference} failed, pls try again"];

        }

        if (getRaveMetaValue($tranx['data']['meta'], 'reason') != $reason) {

            return ['error' => "Payment is not for {$reason}"];

        }

        if (Transaction::where('ref', $reference)->first()) {
            return ['error' => "Payment {$reference} already approved"];

        }

        return $tranx;

    }

    public static function validateHookPayment($reference)
    {

        if (!$reference) {
            return ['error' => 'No reference supplied'];
        }

// initiate the Library's Paystack Object
        //$paystack = new Paystack(env('PAYSTACK_SECRET'));
        try
        {
            // verify using the library
            /*  $tranx = $paystack->transaction->verify([
            'reference' => $reference, // unique to transactions
            ]); */
            $tranx = self::validateReference($reference);

        } catch (\Throwable $e) {

            /* \Paystack\Exception\ApiException $e */
            // print_r($e->getResponseObject());
            return ['error' => $e->getMessage()];
        }

        if ('success' != $tranx['status'] || '00' != $tranx['data']['chargecode']) {
            return ['error' => "Payment {$reference} failed, pls try again"];

        }

        return $tranx;

    }

}