<?php

namespace App\Http\Controllers;

// use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Bookmark;
use App\Tag;
use App\Policies\BookmarkPolicy;

class BookmarksController extends Controller
{
    public function __construct()
    {
        // Authorization middleware (if the user does not have appropriate permissions, do a not currently logged in error)
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('can:update,bookmark')->only(['edit', 'update', 'attachTag', 'detachTag']);
        $this->middleware('can:create,App\Bookmark')->only(['create', 'store']);
        $this->middleware('can:delete,bookmark')->only('destroy');
    }

    // Show all bookmarks
    function index()
    {
        $user = auth()->check() ? auth()->user() : null;
        $bookmarks = Bookmark::getFilteredBookmarks($user);

        return view (
            'bookmarks.index',
            compact('bookmarks')
        );
    }

    // Allow user to create a new bookmark
    public function create()
    {
        // Ensure user is able to create new bookmarks before proceeding
        $this->authorize('create', Bookmark::class);
        return view('bookmarks.create');
    }

    // Save a bookmark (will redirect to show that specific bookmark's details)
    public function store(Request $request)
    {
        // Ensure user is able to create new bookmarks before proceeding
        $this->authorize('create', Bookmark::class);

        // Validate the request inputs first
        $attributes = $request->validate([
            'title' => ['required', 'min:3'],
            'url' => ['required', 'url'],
            'description' => ['min:3'],
        ]);

        $bookmark = new Bookmark;

        // Fill out the bookmark with the appropriate data
        // Assign the bookmark user to the currently logged in user, and
        // Assign the is_public of the bookmark to true if the request has an is_public field
        // then save it
        $bookmark->fill($attributes);
        $bookmark->user_id = auth()->user()->id;

        $bookmark->is_public = $request->has('is_public');
        $bookmark->save();
        return redirect(route('bookmarks.show', $bookmark));
    }

    // Allow user to view a new specific bookmark
    public function show(Bookmark $bookmark)
    {
        $this->authorize('view', $bookmark);
        return view('bookmarks.show', compact('bookmark'));
    }

    // Loads the edit page using the selected bookmark
    public function edit(Bookmark $bookmark)
    {
        $this->authorize('update', $bookmark);
        return view('bookmarks.edit', compact('bookmark'));
    }

    // Performs an update to the provided bookmark
    public function update(Bookmark $bookmark)
    {
        $this->authorize('update', $bookmark);
        // validate the bookmark first
        $attributes = request()->validate([
            'title' => ['required', 'min:3'],
            'url' => ['required', 'url'],
            'description' => ['min:3'],
        ]);
        // Prevent hidden inputs from updating the user_id by overriding it with the owner of the bookmark
        $attributes["user_id"] = $bookmark->user_id;

        // Update the bookmark with the validated attributes, then redirect to show the specific bookmark
        $bookmark->update($attributes);

        // Apply is_public tag to the updated bookmark then save it
        $bookmark->is_public = request()->has('is_public');
        $bookmark->save();

        return redirect(route('bookmarks.index'));
    }

    // Delete the selected bookmark
    public function destroy(Bookmark $bookmark)
    {
        $this->authorize('delete', $bookmark);
        $bookmark->delete();

        return redirect('bookmarks');
    }

    /**
     * Used to attach a tag to a bookmark
     * Requires a tag name in the request
     *
     * @param \Illuminate\Http\Request
     * @param \App\Bookmark $bookmark
     * @return \Illuminate\Http\Response
     */
    public function attachTag(Request $request, Bookmark $bookmark)
    {
        $attributes = $request->validate([
            'name' => 'required|min:3',
        ]);

        $bookmark->attachTag($attributes['name']);
        return back();
    }

    /**
     * Used to detach a tag from the bookmark
     *
     * @param App\Bookmark $bookmark
     * @param App\Tag $tag
     * @return \Illuminate\Http\Response
     */
    public function detachTag(Bookmark $bookmark, Tag $tag)
    {
        $bookmark->detachTag($tag);
        return back();
    }
}
