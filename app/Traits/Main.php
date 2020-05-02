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

        $seconds = request()->wantsJson() ? 60 : 180;
        if ($dublicate) {
            if (now()->diffInSeconds($dublicate->created_at) < $seconds);
            {
                return true;
            }
        }

        return false;

    }
}