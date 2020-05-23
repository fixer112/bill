<?php

use App\Transaction;

trait Notify
{
    public static function chargeSms(Transaction $tran, $unit = 1)
    {
        $amount = env('SMS_CHARGE', 3) * $unit;
        $tran->sms_notifications->create([
            'amount' => $amount,
            'user_id' => $tran->user->id,

        ]);

        $tran->user->update(['balance' => $user->balance - $amount]);
    }
}