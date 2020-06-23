<?php

namespace App\Policies;

use App\SmsGroup;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SmsGroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\SmsGroup  $smsGroup
     * @return mixed
     */
    public function view(User $user, SmsGroup $smsGroup)
    {
        return $user->id == $smsGroup->user_id || (!$model->is_admin && $user->can('view user'));

    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\SmsGroup  $smsGroup
     * @return mixed
     */
    public function update(User $user, SmsGroup $smsGroup)
    {
        return $user->id == $smsGroup->user_id || (!$model->is_admin && $user->can('view user'));

    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\SmsGroup  $smsGroup
     * @return mixed
     */
    public function delete(User $user, SmsGroup $smsGroup)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\SmsGroup  $smsGroup
     * @return mixed
     */
    public function restore(User $user, SmsGroup $smsGroup)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\SmsGroup  $smsGroup
     * @return mixed
     */
    public function forceDelete(User $user, SmsGroup $smsGroup)
    {
        //
    }

    public function before($user, $ability)
    {

        if ($user->hasAnyRole(['super admin'])) {
            return true;
        }
    }
}