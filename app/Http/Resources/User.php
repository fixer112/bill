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
        $data['bills'] = config('settings.bills');
        $data['bills']['cable'] = getCable();
        $data['airtime_discount'] = airtimeDiscount(U::find($this->id));
        $data['data_discount'] = dataDiscount(U::find($this->id));
        $data['cable_discount'] = cableDiscount(U::find($this->id));

        return $data;
    }
}