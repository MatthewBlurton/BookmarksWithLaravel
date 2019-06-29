<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the user model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        return $user->id === $model->id
            || $user->hasPermissionTo('access all users');
    }

    /**
     * Determine whether the user can update the profile model.
     *
     * @param  \App\User  $user
     * @param  \App\Profile  $profile
     * @return mixed
     */
    public function updateProfile(User $user, Profile $profile)
    {
        return $user->id === $profile->user_id
            || $user->hasPermissionTo('access all users');
    }

    /**
     * Determine whether the currently logged in user can assign the target user a new role
     * @param \App\User $user
     * @param \App\User $targer
     * @return bool
     */
    public function assignRole(User $user, User $target)
    {
        // Check if target has lower privelages than the current user
        if ($user->hasAllPermissions('assign role', 'access all ordinary users'))
        {
            return !$target->hasAllPermissions('assign role', 'access all ordinary users')
                || !$target->hasAllPermissions('assign role', 'access all users');
        }
        return $user->hasAllPermissions('assign role', 'access all users')
            && !$target->hasAllPermissions('assign role', 'access all users');
    }

    /**
     * Determine whether the user can suspend another user.
     * Checks if the user has the 'delete user' and 'access all users' position,
     * and ensures that the user doesn't have the same id of the target to prevent suspending the
     * logged in users account.
     * @param  \App\User  $user
     * @param  \App\User  $target
     * @return bool
     */
    public function suspend(User $user, User $target)
    {
        // Check if target has lower privelages than the current user
        if ($user->hasAllPermissions('suspend user', 'access all ordinary users'))
        {
            return !$target->hasAllPermissions('suspend user', 'access all ordinary users')
                || !$target->hasAllPermissions('suspend user', 'access all users');
        }
        return $user->hasAllPermissions('suspend user', 'access all users')
            && !$target->hasAllPermissions('suspend user', 'access all users');
    }
}
