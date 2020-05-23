<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsNotification extends Model
{
    public function transaction()
    {
        return $this->belongsTo('App\Transaction');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
