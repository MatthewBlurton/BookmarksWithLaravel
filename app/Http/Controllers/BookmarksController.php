<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bookmark;

class BookmarksController extends Controller
{
    public function __construct()
    {
        // Put authentication middleware here
    }

    // Show all bookmarks
    function index()
    {
        $bookmarks = Bookmark::all();

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

        // Create a new bookmark with the validated attributes, then redirect to show the specific bookmark
        $bookmark = Bookmark::create($attributes);

        return redirect("/bookmarks/$bookmark->id");
    }

    // Allow user to view a new specific bookmark
    public function show(Bookmark $bookmark)
    {
        return view('bookmarks.show', compact('bookmark'));
    }

    // Loads the edit page using the selected bookmark
    public function edit(Bookmark $bookmark)
    {
        return view('bookmarks.edit', compact('bookmark'));
    }

    // Performs an update to the provided bookmark
    public function update(Bookmark $bookmark)
    {
        // validate the bookmark first
        $attributes = request()->validate([
            'title' => ['required', 'min:3'],
            'url' => ['required', 'url'],
            'description' => ['min:3'],
        ]);

        // Update the bookmark with the validated attributes, then redirect to show the specific bookmark
        $bookmark->update($attributes);
        return redirect("/bookmarks/$bookmark->id");
    }

    public function destroy(Bookmark $bookmark)
    {
        $bookmark->delete();

        return redirect('/bookmarks');
    }
}