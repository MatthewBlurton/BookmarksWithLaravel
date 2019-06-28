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
     *
     * @param  \App\User  $user
     * @param  \App\Bookmark  $bookmark
     * @return mixed
     */
    public function view(User $user, Bookmark $bookmark)
    {
        $userIsAuthorized = isset($user) && $this->checkAuthorized($user, $bookmark);
        return $userIsAuthorized || $bookmark->is_public;
    }

    /**
     * Determine whether the user can update the bookmark.
     *
     * @param  \App\User  $user
     * @param  \App\Bookmark  $bookmark
     * @return mixed
     */
    public function update(User $user, Bookmark $bookmark)
    {
        return $this->checkAuthorized($user, $bookmark);
    }

    /**
     * Determine whether the user can delete the bookmark.
     *
     * @param  \App\User  $user
     * @param  \App\Bookmark  $bookmark
     * @return mixed
     */
    public function delete(User $user, Bookmark $bookmark)
    {
        return $this->checkAuthorized($user, $bookmark);
    }

    /**
     * Check if the user own's or has rights to the bookmark
     *
     * @param  \App\User  $user
     * @param  \App\Bookmark  $bookmark
     * @return mixed
     */
    private function checkAuthorized(User $user, Bookmark $bookmark)
    {
        return $user->hasPermissionTo('access all bookmarks')
            || $bookmark->user_id === $user->id;
    }
}
