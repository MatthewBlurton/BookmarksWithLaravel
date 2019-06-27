<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Bookmark;
use App\Tag;

class TagsController extends Controller
{
    public function __construct()
    {
        
    }

    public function index()
    {
        $tags = Tag::all();

        return view('bookmarks.tags', $tags);
    }

    public function update(Bookmark $bookmark)
    {
        $attributes = request()->validate([
            'name' => ['min:3'],
        ]);
        $bookmark->attachTag($attributes['name']);
        return redirect("/bookmarks/$bookmark->id");
    }

    public function destroy(Bookmark $bookmark, Tag $tag)
    {
        $bookmark->detachTag($tag);
        return redirect("/bookmarks/$bookmark->id");
    }
}
