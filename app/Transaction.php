<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getRouteKeyName()
    {
        return 'ref';
    }

    public function subscription()
    {
        return $this->hasOne('App\Subscription');
    }

    public function sms_notifications()
    {
        return $this->hasMany('App\SmsNotification')->ordered();
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}