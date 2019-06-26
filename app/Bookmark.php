<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Tag;

class Bookmark extends Model
{
    protected $fillable = [
        'title', 'url', 'description'
    ];

    // protected $guarded = [];
    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }

    public function attachTag($tagName)
    {
        $tag = Tag::firstOrCreate(['name' => $tagName]);
        $this->tags()->syncWithoutDetaching([$tag->id]);
    }

    public function detachTag(Tag $tag)
    {
        $this->tags()->detach([$tag->id]);
        if ($tag->bookmarks()->count() == 0)
        {
            $tag->delete();
        }
    }

}
