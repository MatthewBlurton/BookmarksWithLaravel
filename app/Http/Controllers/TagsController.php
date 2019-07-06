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

    // Display all tags
    public function index()
    {
        // If the user is logged in and the email is verified, show all the tags
        if (auth()->check() && auth()->user()->hasVerifiedEmail()
            && !auth()->user()->hasRole('suspended')) {
            $tags = Tag::orderBy('updated_at', 'DESC')->paginate(30);
        } else {// Otherwise only show 30 of the most popular
            $tags = Tag::orderBy('updated_at', 'DESC')->take(30)->get();
        }

        return view('bookmarks.tags.index', compact('tags'));
    }

    public function show(Tag $tag)
    {
        // If the user is logged in and the email is verified, and the bookmark is public, show each bookmark associated with this tag
        $bookmarks = $tag->getAssociatedFilteredBookmarks();
        return view("bookmarks.tags.show", compact('tag', 'bookmarks'));
    }

    public function update(Bookmark $bookmark)
    {
        $this->authorize('update', $bookmark);
        $attributes = request()->validate([
            'name' => ['min:3'],
        ]);
        $bookmark->attachTag($attributes['name']);
        return back();
    }

    public function destroy(Bookmark $bookmark, Tag $tag)
    {
        $this->authorize('update', $bookmark);
        $bookmark->detachTag($tag);
        return back();
    }
}
