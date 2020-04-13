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
        return config("settings.referral.commision.{$level}");
    }
}