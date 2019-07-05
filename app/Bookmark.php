<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Tag;

class Bookmark extends Model
{
    protected $fillable = [
        'title', 'url', 'description', 'is_public', 'user_id'
    ];

    // protected $guarded = [];
    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Used to gather a set of bookmarks based on the currently logged in user's permissions.
     * Uses pagination for authenticated, non-suspended users
     * @return App\Bookmark
     */
    public static function getFilteredBookmarks()
    {
        // Get the user based on whether the guard is the api page, or a web page
        $user = auth()->user();
        if (auth()->guard('api')->check())
        {
            $user = auth()->guard('api')->user();
        }

        // Paginate check: is the user a guest, has the user verified their email, and is the user not suspended?
        if ($user && $user->hasVerifiedEmail() && !$user->hasRole('suspended'))
        {
            // Check if the user has permission to access all bookmarks, then return all the bookmarks no matter if they are private or public
            // Otherwise only return the bookmark if any of the following conditions are true
            // 1. The bookmark is owned by the currently logged in user
            // 2. The bookmark is set to public
            return $user->hasPermissionTo('access all bookmarks')
                ? Bookmark::orderBy('updated_at', 'DESC')->paginate(10)
                : Bookmark::where('user_id', $user->id)->orWhere('is_public', true)
                    ->orderBy('updated_at', 'DESC')->paginate(10);
        }
        else { // If the paginate check fails, only grab 10 of the latest PUBLIC bookmarks.
            return Bookmark::where('is_public', true)->orderBy('updated_at', 'DESC')
                ->take(10)->get();
        }
    }

    // Attach or create a new tag for this bookmark
    public function attachTag($tagName)
    {
        $tag = Tag::firstOrCreate(['name' => $tagName]);
        // Manually sync updated_at of tag to current time
        $tag->touch();
        $this->tags()->syncWithoutDetaching([$tag->id]);
    }

    // Detach an existing tag
    public function detachTag(Tag $tag)
    {
        $this->tags()->detach([$tag->id]);
        // Manually sync updated_at of tag to current time
        $tag->touch();
    }
}
