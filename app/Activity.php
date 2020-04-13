<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function admin()
    {
        return $this->belongsTo('App\User', 'admin_id');
    }

    public function by(User $user)
    {
        return $this->admin->id == $user->id ? 'self' : $this->admin->username;
    }

    public function to(User $user)
    {
        return $this->user->id == $user->id ? 'self' : $this->user->username;
    }
}
