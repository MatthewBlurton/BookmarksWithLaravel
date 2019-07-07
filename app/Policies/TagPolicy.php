<?php

namespace App\Policies;

use App\User;
use App\Tag;
use Illuminate\Auth\Access\HandlesAuthorization;

class TagPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the tag.
     *
     * @param  \App\User  $user
     * @param  \App\Tag  $tag
     * @return bool
     */
    public function delete(User $user, Tag $tag)
    {
        return $user->hasAllPermissions('delete tag', 'access all tags');
    }
}
