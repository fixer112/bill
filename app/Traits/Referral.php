<?php
namespace App\Traits;

trait Referral
{
    /**
     * Get back commision amount.
     *
     * @param  int  $level
     *  @return  array
     */

    public static function referralCommision(int $level)
    {
        $comissions = config("settings.referral.commision");
        return $comissions[$level] ?? 0;
    }
}