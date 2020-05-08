<?php

namespace App;

use App\Referral;
use App\Traits\Referral as Refer;
use Devi\MultiReferral\Models\ReferralList;
use Devi\MultiReferral\Traits\MultiReferral;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, MultiReferral, Refer;

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
        return ReferralList::whereUserId($this->id)->orderBy("level", "desc")->orderBy("created_at", "desc")->get()->unique('ref_id');

    }

    public function status()
    {
        return $this->is_active ? 'Active' : 'Suspended';
    }
    public function userPackage()
    {
        if ($this->is_admin) {
            return 'admin';
        }

        if (!$this->is_reseller) {
            return 'individual';

        }

        if (!$this->lastSub()) {
            return 'reseller (Awaiting)';
        }

        return $this->lastSub()->name;

    }

    public function lastSub()
    {
        return $this->subscriptions->first();
    }

    public function upgradeList()
    {
        $keys = array_keys(config("settings.subscriptions"));
        if ($this->is_reseller) {
            //$sub = config("subscriptions.{$this->lastSub()->name}");
            $key = array_search($this->lastSub()->name, $keys);
            $lastSub = config("settings.subscriptions.{$this->lastSub()->name}");

            $upgrades = [];

            foreach ($keys as $k => $value) {
                if ($key >= $k) {
                    // unset($keys[$k]);
                    continue;
                }

                $sub = config("settings.subscriptions.{$value}");

                $sub['amount'] = $sub['amount'] - $lastSub['amount'];

                $upgrades[$value] = $sub;
                //array_push($upgrades, [$value => $sub]);

            }
            //unset($keys[$key]);

            return $upgrades;
            // return $keys;

        }

        return config("settings.subscriptions");
        //return "";
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

                if ($u && $level == 1) {
                    array_push($users, ['user' => $u, 'level' => $level, 'comission' => $comission]);
                }

            }
        }
        return collect($users);

    }
    public function giveReferralBounus(float $amount, String $desc, bool $isReferral = false, float $multiples = 1.0)
    {
        $this->getReferralParents()->each(function ($parent) use ($amount, $desc, $isReferral, $multiples) {

            $comission = $parent['comission'];
            $u = $parent['user'];
            $cA = !$isReferral ? calPercentageAmount($amount, ($comission['bonus'] * $multiples)) : calPercentageAmount($amount, ($comission['refer_bonus'] * $multiples));

            $referedBefore = Referral::where('user_id', $u->id)->where('referral_id', $this->id)->exists();
            $cummulative = $this->transactions->where('type', 'credit')->where('reason', 'top-up')->sum('amount');

            if ($isReferral) {
                if ($referedBefore || $cummulative < 1000) {
                    return;
                }
            }

            $u->update([
                'referral_balance' => $u->referral_balance + $cA,
                'points' => $u->points + ($comission['point'] * $multiples),
            ]);

            Referral::create([
                'user_id' => $u->id,
                'amount' => $cA,
                'balance' => $u->referral_balance,
                'referral_id' => $this->id,
                'level' => $parent['level'],
                'desc' => $desc,
                'ref' => generateRef($u),
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

    /* public function getDiscount()
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

    } */
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

    public function minFund()
    {
        if (!$this->is_reseller) {
            return 200;
        }

        $sub = config("settings.subscriptions.{$this->lastSub()->name}");

        return calPercentageAmount($sub['amount'], $sub['bonus']);
    }

    public function profilePic()
    {
        return $this->profile == '' ? '/assets/images/user/avatar-2.jpg' : '/storage/' . $this->profile;
    }

    public function userActivities()
    {
        return $this->hasMany('App\Activity')->ordered();
    }

    public function adminActivities()
    {
        return $this->hasMany('App\Activity', 'id', 'admin_id')->ordered();
    }

    public function transactions()
    {
        return $this->hasMany('App\Transaction')->ordered();
    }

    public function subscriptions()
    {
        return $this->hasMany('App\Subscription')->ordered();
    }

    public function referrals()
    {
        return $this->hasMany('App\Referral')->ordered();
    }

    public function type()
    {
        return $this->is_reseller ? 'Reseller' : 'Individual';
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

}