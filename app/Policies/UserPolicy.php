<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Check if the authenticated user is the current user.
     *
     * @param \App\User $user
     * @param \App\User $target
     * @return bool
     */
    public function authIsUser(User $user, User $target)
    {
        return $user->id === $target->id;
    }

    /**
     * Determine whether the user can create other users.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasAnyPermission('access all accounts', 'access all users') && $user->hasPermissionTo('add users');
    }

    /**
     * Determine whether the user can update the user model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $target
     * @return bool
     */
    public function update(User $user, User $target)
    {
        return $user->id === $target->id
            || $this->checkPermissions($user, $target, 'edit users');
    }

    /**
     * Determine whether the user can view sensitive information relating to a target account.
     * Sensitive information includes: Emails, First Name, and Last Name
     *
     * @param \App\User $user
     * @param \App\User $target
     * @return boolean
     */
    public function viewSensitive(User $user, User $target)
    {
        // If the user is not suspended, and has permission to read profiles, or the target is the user then the user is allowed to view sensetive information
        if (!$user->hasRole('suspended') && $this->checkPermissions($user, $target, 'read profiles')) {
            return true;
        } else if ($user->id === $target->id) {
            return true;
        }
        return false;
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
     * Determine whether the user can do elevated changes to the user model.
     * This includes:
     * Changing the user's password without needing to know the old user password
     * Changing the user's username
     * Changing the user's email address
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function updateElevate(User $user, User $target)
    {
        return $user->id !== $target->id && $this->checkPermissions($user, $target, 'edit users');
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
            return !$target->hasAnyPermission('access all accounts', 'access all users');
        }
        // return true if the user has permission to access all accounts
        return $user->hasAllPermissions($action, 'access all accounts');
    }
}
