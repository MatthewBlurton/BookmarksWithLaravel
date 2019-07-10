<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Bookmark;
use App\Tag;

class TagsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:delete,tag'])->only('destroy');
    }

    // Display a set of ten tags
    public function index()
    {
        $tags = Tag::getFilteredTags(auth()->user());

        return view('bookmarks.tags.index', compact('tags'));
    }

    public function show(Tag $tag)
    {
        // If the user is logged in and the email is verified, and the bookmark is public, show each bookmark associated with this tag
        $bookmarks = $tag->getAssociatedFilteredBookmarks(auth()->user());
        return view("bookmarks.tags.show", compact('tag', 'bookmarks'));
    }

    /**
     * Deletes the selected tag. Any bookark associated with this tag will lose their assoication to this tag
     *
     * @param \App\Tag $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        $tagName = isset($tag) ? $tag->name : null;
        if ($tag->delete()) {
            return redirect(route('tags.index'))->with(['success' => $tagName . ' successfully deleted']);
        };
        return redirect(route('tags.index'))->withErrors(['message' => 'Failed to delete tag']);
    }
}
