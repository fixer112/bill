<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
        return $user->can('view all users');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function view(User $user, User $model)
    {
        //return true;
        return $user->id == $model->id || (!$model->is_admin && $user->can('view user'));
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return ($user->can('create user'));
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        return $user->id == $model->id || (!$model->is_admin && $user->can('update user'));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        return (!$model->is_admin && $user->can('delete user'));
    }

    public function suspend(User $user, User $model)
    {
        return (!$model->is_admin && $user->can('suspend user'));
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function restore(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }

    public function upgrade(User $user, User $model)
    {
        //return true;
        return $user->id == $model->id && count($model->upgradeList()) > 0; //&& $model->is_reseller;
    }

    public function reseller(User $user, User $model)
    {
        return $user->id == $model->id && $model->is_reseller;
    }

    public function massMail(User $user)
    {
        return $user->can('send mass mail');
    }

    public function debit(User $user, User $model)
    {
        return (!$model->is_admin && $user->can('debit wallet'));
    }

    public function fund(User $user, User $model)
    {
        return (!$model->is_admin && $user->can('fund wallet'));
    }

    public function manageRoles(User $user)
    {
        return $user->can('manage roles');
    }

    public function before($user, $ability)
    {

        if ($user->hasAnyRole(['super admin'])) {
            return true;
        }
    }
}