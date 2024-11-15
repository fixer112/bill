<?php

namespace App\Http\Resources;

use App\User as U;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        //$data['settings'] = config('settings');
        $data['profile_pic'] = $this->profilePic();
        $data['full_name'] = $this->full_name;
        $data['settings']['bills'] = config('settings.bills');
        $data['settings']['bills']['cable'] = getCable();
        $data['settings']['airtime_discount'] = airtimeDiscount(U::find($this->id));
        $data['settings']['data_discount'] = dataDiscount(U::find($this->id));
        $data['settings']['cable_discount'] = cableDiscount(U::find($this->id));
        $data['settings']['electricity_discount'] = electricityDiscount(U::find($this->id));
        $data['settings']['electricity_discount_multiple'] = env('ELECTRICITY_DISCOUNT_MULTIPLE', 5000);
        $data['settings']['airtime_alert'] = env("AIRTIME_ALERT");
        $data['settings']['data_alert'] = env("DATA_ALERT");
        $data['settings']['cable_alert'] = env("CABLE_ALERT");
        $data['settings']['electricity_alert'] = env("ELECTRICITY_ALERT");
        $data['settings']['general_alert'] = env("GENERAL_ALERT_APP");
        $data['latest_comissions'] = $this->referrals->take(config("settings.recent_page"));
        $data['latest_transactions'] = $this->transactions->take(config("settings.recent_page"));
        $data['package_name'] = $this->userPackage();
        $data['settings']['transfer_fee'] = env("MONIFY_FEE", 2);
        $data['settings']['rave_public_key_app'] = env("RAVE_PUBLIC_KEY_APP", env("RAVE_PUBLIC_KEY"));
        $data['settings']['rave_enc_key_app'] = env("RAVE_ENC_KEY_APP", env("RAVE_ENC_KEY"));
        $data['settings']['min_fund'] = env('MIN_FUND_APP', 200);
        $data['settings']['max_fund'] = env('MAX_FUND_APP', 0);
        $data['settings']['ref_link'] = $this->getReferralLink();
        $data['settings']['enable_online_payment'] = env("ENABLE_ONLINE_PAYMENT_APP", false);

        return $data;
    }
}