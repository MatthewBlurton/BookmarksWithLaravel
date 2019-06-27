<?php

namespace App\Http\Controllers;

// use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Bookmark;
use App\Policies\BookmarkPolicy;

class BookmarksController extends Controller
{
    public function __construct()
    {
        // Authorization middleware (if the user does not have appropriate permissions, do a not currently logged in error)
        $this->middleware(['permission:edit bookmarks'])->only('edit|update');
        $this->middleware(['permission:add bookmarks'])->only('create|store');
        $this->middleware(['permission:delete bookmarks'])->only('destroy');
    }

    // Show all bookmarks
    function index()
    {
        // Only grab bookmarks when it is either associated with the current logged in user, or if the bookmark is public
        $bookmarks = auth()->user()->hasPermissionTo('breads all bookmarks') ? Bookmark::all() :
            Bookmark::where('user_id', auth()->id())->orWhere('is_public', true)->get();

        return view (
            'bookmarks.index',
            compact('bookmarks')
        );
    }

    // Allow user to create a new bookmark
    public function create ()
    {
        return view('bookmarks.create');
    }

    // Save a bookmark (will redirect to show that specific bookmark's details)
    public function store(Request $request)
    {
        // Validate the request inputs first
        $attributes = request()->validate([
            'title' => ['required', 'min:3'],
            'url' => ['required', 'url'],
            'description' => ['min:3'],
        ]);

        // Create a new bookmark with the attributes and the currently logged in user id. Then redirect to show details of the newly created bookmark
        $attributes["user_id"] = auth()->id();
        $bookmark = Bookmark::create($attributes);

        return redirect("/bookmarks/$bookmark->id");
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
        // Prevent hidden inputs from updating the user_id by overriding it with the currently logged in user
        $attributes["user_id"] = auth()->id();
        // Update the bookmark with the validated attributes, then redirect to show the specific bookmark
        $bookmark->update($attributes);
        return redirect("/bookmarks/$bookmark->id");
    }

    // Delete the selected bookmark
    public function destroy(Bookmark $bookmark)
    {
        $this->authorize('delete', $bookmark);
        $bookmark->delete();

        return redirect('/bookmarks');
    }
}