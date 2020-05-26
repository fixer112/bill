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
        $data['full_name'] = $this->full_name;
        $data['settings']['bills'] = config('settings.bills');
        $data['settings']['bills']['cable'] = getCable();
        $data['settings']['airtime_discount'] = airtimeDiscount(U::find($this->id));
        $data['settings']['data_discount'] = dataDiscount(U::find($this->id));
        $data['settings']['cable_discount'] = cableDiscount(U::find($this->id));
        $data['latest_comissions'] = Referral::ordered()->get()->take(config("settings.recent_page"));
        $data['latest_transactions'] = Transaction::ordered()->get()->take(config("settings.recent_page"));

        return $data;
    }
}