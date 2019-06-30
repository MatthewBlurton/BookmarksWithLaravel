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
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('permission:edit bookmarks')->only(['edit', 'update']);
        $this->middleware('permission:add bookmarks')->only(['create', 'store']);
        $this->middleware('permission:delete bookmarks')->only('destroy');
        
    }

    // Show all bookmarks
    function index()
    {
        $bookmarks = [];

        // Only grab bookmarks when it is either associated with the current logged in user, or if the bookmark is public
        if (auth()->check() && auth()->user()->hasVerifiedEmail()) {
            $bookmarks = auth()->user()->hasPermissionTo('access all bookmarks')
                        ? Bookmark::orderBy('updated_at', 'DESC')->paginate(10)
                        : Bookmark::where('user_id', auth()->id())->orWhere('is_public', true)
                            ->orderBy('updated_at', 'DESC')->paginate(10);
        }
        else {// If the user is a guest or not verified, only show 10 of the most recent bookmarks
            $bookmarks = Bookmark::where('is_public', true)->orderBy('updated_at', 'DESC')
                            ->take(10)->get();
        }

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

        // Create a new bookmark with the attributes and the currently logged in user id. Then redirect to show details of the newly created bookmark
        $attributes["user_id"] = auth()->id();
        $bookmark = Bookmark::create($attributes);

        // Apply is_public boolean to the newly created bookmark and save it
        $bookmark->is_public = $request->has('is_public');
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
}