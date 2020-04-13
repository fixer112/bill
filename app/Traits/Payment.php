<?php
namespace App\Traits;

use Yabacon\Paystack;
/**
 *
 */
trait Payment
{
    public static function validatePayment($reference)
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

            return $tranx;
        } catch (\Throwable $e) {

            /* \Paystack\Exception\ApiException $e */
            // print_r($e->getResponseObject());
            return ['error' => $e->getMessage()];
        }

        if ('success' != $tranx->data->status) {
            return ['error', "Payment {$reference} failed, pls try again"];

        }
    }
}