<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsHistory extends Model
{
    protected $guarded = [];

    public function transaction()
    {
        return $this->belongsTo('App\Transaction');
    }

    public function sms_group()
    {
        return $this->belongsTo('App\SmsGroup');
    }

    public function setNumbersAttribute($value)
    {
        $this->attributes['numbers'] = implode(',', formatPhoneNumberArray($value));

    }
    public function setSuccessNumbersAttribute($value)
    {
        $this->attributes['success_numbers'] = implode(',', formatPhoneNumberArray($value));

    }
    public function setFailedNumbersAttribute($value)
    {
        $this->attributes['failed_numbers'] = implode(',', formatPhoneNumberArray($value));

    }
}
