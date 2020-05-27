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

    function isDublicate(User $user, $amount, $desc, $reason)
    {
        $dublicate = $user->transactions->where('amount', $amount) /* ->where('reason', $reason) */->where('desc', $desc)->first();

        //return $dublicate;

        $seconds = dublicateTime();
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
    function giveReferralBonus($user)
    {
        //$referedBefore = Referral::where('user_id', $u->id)->where('referral_id', $this->id)->exists();
        //$cummulative = $user->transactions->where('type', 'credit')->where('reason', 'top-up')->sum('amount');

        //if ($isReferral) {
        //if (!$referedBefore && $cummulative >= 1000) {

        $user->giveReferralBounus(200, "Referral bonus for first 1000 naira cummultive top-up", true);

        //}
        // }

        /* if ($user->transactions->where('reason', 'top-up')->count() == 1) {
    $user->giveReferralBounus(100, "Referral bonus for first time top-up", true);
    } */

    }
}