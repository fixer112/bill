<?php
namespace App\Traits;

use App\SmsNotification;
use App\Traits\BillPayment;
use App\Transaction;

trait Notify
{
    use BillPayment;

    public static function chargeSms(Transaction $tran, $message)
    {
        $unit = calSmsUnit($message);
        $amount = env('SMS_CHARGE', 3) * $unit;

        if ($tran->user->balance < $amount) {
            return ['error' => 'Insufficient balance to send sms'];
            //new Exception("Insufficient balance to send sms");
        }

        $sms = self::sms($message, $tran->user->nigeria_number);

        SmsNotification::create([
            'amount' => $amount,
            'user_id' => $tran->user->id,
            'transaction_id' => $tran->id,

        ]);

        $tran->user->update(['balance' => $tran->user->balance - $amount]);
    }
}