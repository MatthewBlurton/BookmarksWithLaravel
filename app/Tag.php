<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
     * Gathers all the bookmarks associated with this tag, then filters them based on
     * whether it is owned by the user, whether the user has access to all bookmarks,
     * or if the user is a guest or suspended.
     * @return App\Bookmark
     */
    public function getAssociatedFilteredBookmarks()
    {
        // Get the user based on whether the guard is the api page, or a web page
        $user = auth()->user();
        if (auth()->guard('api')->check())
        {
            $user = auth()->guard('api')->user();
        }

        // If the user is logged in and the email is verified, and the bookmark is public, show each bookmark associated with this tag
        if ($user && !$user->hasRole('suspended') && $user->hasVerifiedEmail()) {
            return $user->hasPermissionTo('access all bookmarks')
                ? $tag->bookmarks()->orderBy('updated_at', 'DESC')->paginate(7)
                : $tag->bookmarks()->where('user_id', auth()->id())->orWhere('is_public', true)
                    ->orderBy('updated_at', 'DESC')->paginate(7);

                    // $bookmarks = auth()->user()->hasPermissionTo('access all bookmarks')
                    // ? $tag->bookmarks()->orderBy('updated_at', 'DESC')->paginate(7)
                    // : $tag->bookmarks()->where('user_id', auth()->id())->orWhere('is_public', true)
                    //     ->wherePivot('tag_id', $tag->id)
                    //     ->orderBy('updated_at', 'DESC')->paginate(7);
        } else {// Otherwise only show 30 of the most popular
            return $tag->bookmarks()->where('is_public', true)->orderBy('updated_at', 'DESC')
                        ->take(8)->get();
        }
    }
}
