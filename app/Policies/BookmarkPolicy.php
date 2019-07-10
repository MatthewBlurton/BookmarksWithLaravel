<?php

namespace App\Policies;

use App\User;
use App\Bookmark;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookmarkPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the bookmark.
     * They can view the bookmark if the user ha
     * @param  \App\User $user
     * @param  \App\Bookmark  $bookmark
     * @return mixed
     */
    public function view(?User $user, Bookmark $bookmark)
    {
        // Check if the user is authenticated, and not suspended
        if (isset($user) && !$user->hasRole('suspended') && $user->hasVerifiedEmail()) {
            return $user->hasPermissionTo('access all bookmarks') || $user->id === $bookmark->user_id || $bookmark->is_public;
        } else {
            // Guest or suspended can only see public bookmarks
            return $bookmark->is_public;
        }
    }

     /**
     * Determine whether the user can create bookmarks.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasVerifiedEmail() && $user->hasPermissionTo('add bookmarks');
    }

    /**
     * Determine whether the user can update the bookmark.
     *
     * @param  \App\User  $user
     * @param  \App\Bookmark  $bookmark
     * @return bool
     */
    public function update(User $user, Bookmark $bookmark)
    {
        return $user->hasVerifiedEmail() && $this->checkPermissions($user, $bookmark, 'edit bookmarks');
    }

    /**
     * Determine whether the user can delete the bookmark.
     *
     * @param  \App\User  $user
     * @param  \App\Bookmark  $bookmark
     * @return bool
     */
    public function delete(User $user, Bookmark $bookmark)
    {
        return $this->checkPermissions($user, $bookmark, 'delete bookmarks');
    }

    /**
     * Check if the user own's or has rights to the bookmark
     *
     * @param  \App\User  $user
     * @param  \App\Bookmark  $bookmark
     * @param string $action
     * @return bool
     */
    private function checkPermissions(User $user, Bookmark $bookmark, string $action)
    {
        return $user->hasAllPermissions($action, 'access all bookmarks')
            || ($user->hasPermissionTo($action)
                && $user->id === $bookmark->user_id );
    }
}
