<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsHistory extends Model
{
    protected $guarded = [];

    public function transaction()
    {
        return $this->belongsTo('App\User');
    }

    public function sms_group()
    {
        return $this->hasOne('App\SmsGroup');
    }
}
