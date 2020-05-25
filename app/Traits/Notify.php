<?php
namespace App\Traits;

use App\SmsNotification;
use App\Transaction;

trait Notify
{
    public static function chargeSms(Transaction $tran, $unit = 1)
    {
        $amount = env('SMS_CHARGE', 3) * $unit;

        SmsNotification::create([
            'amount' => $amount,
            'user_id' => $tran->user->id,
            'transaction_id' => $tran->id,

        ]);

        $tran->user->update(['balance' => $tran->user->balance - $amount]);
    }
}