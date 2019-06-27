<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class UsersController extends Controller
{
    public function __construct()
    {
        // Authorization middleware (if the user does not have appropriate permissions, do a not currently logged in error)
        $this->middleware(['permission:edit users'])->only('edit|update');
        $this->middleware(['permission:add users'])->only('create|store');
        $this->middleware(['permission:delete users'])->only('destroy');
    }

    // Show all bookmarks
    function index()
    {
        // Only grab bookmarks when it is either associated with the current logged in user, or if the bookmark is public
        $users = User::all();

        return view (
            'users.index',
            compact('users')
        );
    }

    // Save a bookmark (will redirect to show that specific bookmark's details)
    public function store(Request $request)
    {
        // // Validate the request inputs first
        // $attributes = request()->validate([
        //     'title' => ['required', 'min:3'],
        //     'url' => ['required', 'url'],
        //     'description' => ['min:3'],
        // ]);

        // // Create a new bookmark with the attributes and the currently logged in user id. Then redirect to show details of the newly created bookmark
        // $attributes["user_id"] = auth()->id();
        // $bookmark = Bookmark::create($attributes);

        return redirect("users");
    }

    // View a specific user
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    // Modifies the user
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // Performs an update to the selected user
    public function update(User $user)
    {
        return redirect("users.$user->id");
    }

    // Delete the selected bookmark
    public function destroy(User $user)
    {
        return redirect("users.$user->id");
    }

}
