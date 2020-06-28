<?php
namespace App\Traits;

use App\User;

trait Main
{
    public function isReferalBalanceEnuf(User $user, $amount)
    {
        return $amount > $user->referral_balance ? false : true;
    }

    public function isBalanceEnuf(User $user, $amount)
    {
        return $amount > $user->balance ? false : true;
    }

    public function isDublicate(User $user, $amount, $desc, $reason)
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
    public function giveReferralBonus($user)
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

    public static function fundBonus(User $user, $amount)
    {

        if ($user->balance == 0 && $amount >= 500) {
            $bonus = 100;
            $user->transactions()->create([
                'amount' => $bonus,
                'balance' => $user->balance + $bonus,
                'type' => 'credit',
                'desc' => "First time fund bonus",
                'ref' => generateRef($user),
                'user_id' => $user->id,
                'plathform' => getPlathform(),

            ]);
            $user->update([
                'balance' => $user->balance + $bonus,
            ]);

        }
    }

}