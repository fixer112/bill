<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $guarded = [];

    public function transaction()
    {
        return $this->belongsTo('App\Transaction');
    }
}
