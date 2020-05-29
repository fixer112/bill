<?php

namespace App\Http\Resources;

use App\Referral;
use App\Transaction;
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
        $data['settings']['airtime_alert'] = env("AIRTIME_ALERT");
        $data['settings']['data_alert'] = env("DATA_ALERT");
        $data['settings']['cable_alert'] = env("CABLE_ALERT");
        $data['settings']['general_alert'] = env("GENERAL_ALERT");
        $data['latest_comissions'] = Referral::ordered()->get()->take(config("settings.recent_page"));
        $data['latest_transactions'] = Transaction::ordered()->get()->take(config("settings.recent_page"));
        $data['package_name'] = $this->userPackage();
        $data['transfer_fee'] = env("MONIFY_FEE", 2);

        return $data;
    }
}