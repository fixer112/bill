<?php
namespace App\Traits;

use App\Transaction;
use App\User;
use Yabacon\Paystack;
/**
 *
 */
trait Payment
{
    public static function validatePayment($reference, $reason)
    {

        if (!$reference) {
            return ['error' => 'No reference supplied'];
        }

// initiate the Library's Paystack Object
        $paystack = new Paystack(env('PAYSTACK_SECRET'));
        try
        {
            // verify using the library
            $tranx = $paystack->transaction->verify([
                'reference' => $reference, // unique to transactions
            ]);

        } catch (\Throwable $e) {

            /* \Paystack\Exception\ApiException $e */
            // print_r($e->getResponseObject());
            return ['error' => $e->getMessage()];
        }

        if ('success' != $tranx->data->status) {
            return ['error' => "Payment {$reference} failed, pls try again"];

        }

        if (!$user = User::find($tranx->data->metadata->user_id)) {
            return ['error' => 'User does not exist'];

        }

        if ($tranx->data->metadata->reason != $reason) {

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
        $paystack = new Paystack(env('PAYSTACK_SECRET'));
        try
        {
            // verify using the library
            $tranx = $paystack->transaction->verify([
                'reference' => $reference, // unique to transactions
            ]);

        } catch (\Throwable $e) {

            /* \Paystack\Exception\ApiException $e */
            // print_r($e->getResponseObject());
            return ['error' => $e->getMessage()];
        }

        if ('success' != $tranx->data->status) {
            return ['error' => "Payment {$reference} failed, pls try again"];

        }

        if ($tranx->data->metadata->reason != $reason) {

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
        $paystack = new Paystack(env('PAYSTACK_SECRET'));
        try
        {
            // verify using the library
            $tranx = $paystack->transaction->verify([
                'reference' => $reference, // unique to transactions
            ]);

        } catch (\Throwable $e) {

            /* \Paystack\Exception\ApiException $e */
            // print_r($e->getResponseObject());
            return ['error' => $e->getMessage()];
        }

        if ('success' != $tranx->data->status) {
            return ['error' => "Payment {$reference} failed, pls try again"];

        }

        return $tranx;

    }

}