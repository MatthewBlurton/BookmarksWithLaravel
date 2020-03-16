<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;

class Tag extends Model
{
    protected $fillable = [
        'name'
    ];

    public function bookmarks()
    {
        return $this->belongsToMany('App\Bookmark');
    }

    /**
     * If the user is logged in, the email is verified, and the user is not suspended, show all the tags
     * @param App\User $user
     */
    public static function getFilteredTags(?User $user)
    {
        if ($user && $user->hasVerifiedEmail() && !$user->hasRole('suspended')) {
            return Tag::orderBy('updated_at', 'DESC')->paginate(5);
        } else {// Otherwise only show 5 of the most popular
            return Tag::orderBy('updated_at', 'DESC')->take(5)->get();
        }
    }

    /**
     * Gathers all the bookmarks associated with this tag, then filters them based on
     * whether it is owned by the user, whether the user has access to all bookmarks,
     * or if the user is a guest or suspended.
     *
     * @param App\User $user
     * @return App\Bookmark
     */
    public function getAssociatedFilteredBookmarks(?User $user = null)
    {
        // If the user is logged in and the email is verified, and the bookmark is public, show each bookmark associated with this tag
        if ($user && !$user->hasRole('suspended') && $user->hasVerifiedEmail()) {
            return $user->hasPermissionTo('access all bookmarks')
                ? $this->bookmarks()->wherePivot('tag_id', $this->id)->orderBy('updated_at', 'DESC')->paginate(5)
                : $this->bookmarks()->wherePivot('tag_id', $this->id)->whereRaw('(user_id = ' . auth()->id() . ' || is_public = true)')
                    ->orderBy('updated_at', 'DESC')->paginate(5);
        } else {// Otherwise only show 5 of the most popular
            return $this->bookmarks()->wherePivot('tag_id', $this->id)->where('is_public', true)->orderBy('updated_at', 'DESC')
                        ->take(5)->get();
        }
    }
}
