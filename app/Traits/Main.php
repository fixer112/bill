<?php
namespace App\Traits;

use App\User;

trait Main
{
    function isReferalBalanceEnuf(User $user, $amount)
    {
        return $amount > $user->referral_balance ? false : true;
    }

    function isBalanceEnuf(User $user, $amount)
    {
        return $amount > $user->balance ? false : true;
    }

    function isDublicate(User $user, $amount, $reason)
    {
        $dublicate = $user->transactions->where('amount', $amount)->where('reason', $reason)->first();

        //return $dublicate;

        $seconds = request()->wantsJson() ? 60 : 180;
        if ($dublicate) {
            //return now()->diffInSeconds($dublicate->created_at);
            //return $seconds;
            $time = now()->diffInSeconds($dublicate->created_at);
            if ($time < $seconds) {
                return true;
            }
            //return 'false';

        }

        return false;

    }
}