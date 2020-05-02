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

        if ($dublicate) {
            if (now()->diffInMinutes($dublicate->created_at) < 3);
            {
                return true;
            }
        }

        return false;

    }
}