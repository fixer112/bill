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

}