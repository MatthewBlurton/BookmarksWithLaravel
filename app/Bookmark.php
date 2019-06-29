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
