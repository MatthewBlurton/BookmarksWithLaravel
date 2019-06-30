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
    public function update(User $user, User $target)
    {
        return $user->id === $target->id
            || $this->checkPermissions($user, $target, 'edit users');
    }

    /**
     * Determine whether the user can update the profile model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $profile
     * @return mixed
     */
    public function updateProfile(User $user, User $target)
    {
        return $user->id === $target->id
            || $this->checkPermissions($user, $target, 'edit profiles');
    }

    /**
     * Determine whether the user can do elevated to the user model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function updateElevate(User $user, User $target)
    {
        return $user->id !== $target->id && $this->checkPermissions($user, $target, 'assign role');
    }

    /**
     * Determine whether the currently logged in user can assign the target user a new role
     * @param \App\User $user
     * @param \App\User $targer
     * @return bool
     */
    public function assignRole(User $user, User $target)
    {
        return $user->id !== $target->id
            && !$target->hasRole('suspended')
            && !$user->hasPermissionTo('access all ordinary users')
            && $this->checkPermissions($user, $target, 'assign role');
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
        return $user->id !== $target->id && $this->checkPermissions($user, $target, 'suspend user');
    }

    /**
     * Check that the user has the necessary permissions and that those permissions don't conflict with the target user
     */
    private function checkPermissions(User $user, User $target, string $action)
    {
        // Ensure user admins can't change other user admins, and admins
        if ($user->hasAllPermissions($action, 'access all ordinary users'))
        {
            return !$target->hasAnyPermission('access all ordinary users', 'access all users', 'access all accounts');
        }// Ensure admins can't change other admins
        else if ($user->hasAllPermissions($action, 'access all users'))
        {
            return !$target->hasAnyPermission('access all users', 'access all accounts');
        }
        // return true if the user has permission to access all accounts
        return $user->hasAllPermissions($action, 'access all accounts');
    }
}
