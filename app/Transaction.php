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
    public function sms_history()
    {
        return $this->hasOne('App\SmsHistory');
    }

    public function sms_notifications()
    {
        return $this->hasMany('App\SmsNotification')->ordered();
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
    public function statusColor()
    {
        switch ($this->status) {
            case 'approved':
                return 'success';
                break;
            case 'pending':
                return 'primary';
                break;
            case 'failed':
                return 'danger';
                break;
            default:

                break;
        }

    }
}