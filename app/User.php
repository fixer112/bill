<?php

namespace App;

use App\Traits\Referral;
use Devi\MultiReferral\Models\ReferralList;
use Devi\MultiReferral\Traits\MultiReferral;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, MultiReferral, Referral;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    /* protected $fillable = [
    'name', 'email', 'password',
    ]; */

    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getReferralChildren()
    {
        return ReferralList::whereUserId($this->id)->orderBy("level", "desc")->orderBy("created_at", "desc")->get()->unique('user_id');

    }

    public function getReferralParents()
    {
        $parents = $this->findAndSaveAllParents();
        $users = [];

        if (!empty($parents)) {
            foreach ($parents as $parent) {
                $userId = $parent['user_id'];
                $level = $parent['level'];
                $comission = $this->referralCommision($level);
                $u = User::find($userId);

                if ($u) {
                    array_push($users, ['user' => $u, 'level' => $level, 'comission' => $comission]);
                }

            }
        }
        return collect($users);

    }
    public function giveReferralBounus(String $desc, float $multiples = 1.0)
    {
        $this->getReferralParents()->each(function ($parent) {

            $comission = $parent['comission'];
            $u = $parent['user'];
            $cA = calPercentageAmount($amount, ($comission['bonus'] * $multiples));
            $u->update([
                'referral_balance' => $u->comision_balance + $cA,
                'points' => $u->points + ($comission['point'] * $multiples),
            ]);

            Referral::create([
                'user_id' => $u->id,
                'amount' => $cA,
                'balance' => $u->comision_balance,
                'referral_id' => $user->id,
                'level' => $parent['level'],
                'desc' => $desc,
            ]);
        });
    }

    public function routePath()
    {
        return $this->is_admin ? '/admin' : "/user/{$this->id}";
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = /* Hash::needsRehash($value) ? */bcrypt($value) /* : $value */;
    }

    public function getFullNameAttribute()
    {
        return $this->attributes['first_name'] . ' ' . $this->attributes['last_name'];
    }

    public function isMode()
    {
        return $this->is_admin ? true : false;
    }
    public function calDiscount()
    {
        //return $this->is_reseller;

        if (!$this->is_reseller) {
            return 0;
        }

        $subscription = $this->subscriptions->last();
        if (!$subscription) {
            return 0;
        }

        $discount = config("settings.subscriptions.{$subscription->name}.discount");

        return $discount;

    }
    public function getReferralLevel()
    {
        $points = $this->points;
        $levels = config("settings.referral.levels");

        $values = array_values($levels);
        $keys = array_keys($levels);

        for ($i = 0; $i < count($values); $i++) {

            if ($points <= $values[0]) {
                return $keys[0];
                break;

            }

            if ($points >= $values[count($values) - 1]) {
                return $keys[count($keys) - 1];
                break;

            }

            if ($points >= $values[$i] && $points < $values[$i + 1]) {
                return $keys[$i];
                break;

            }

        }

    }

    public function profilePic()
    {
        return $this->profile == '' ? '/assets/images/user/avatar-2.jpg' : '/storage/' . $this->profile;
    }

    public function userActivities()
    {
        return $this->hasMany('App\Activity');
    }

    public function adminActivities()
    {
        return $this->hasMany('App\Activity', 'id', 'admin_id');
    }

    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }

    public function subscriptions()
    {
        return $this->hasMany('App\Subscription');
    }

    public function referrals()
    {
        return $this->hasMany('App\Referral');
    }

    public function type()
    {
        return $this->is_reseller ? 'Reseller' : 'Individual';
    }

}