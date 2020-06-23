<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsGroup extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function setNumbersAttribute($value)
    {
        $this->attributes['numbers'] = implode(',', formatPhoneNumberArray($value));

    }

}