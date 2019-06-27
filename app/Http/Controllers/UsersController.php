<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class UsersController extends Controller
{
    public function __construct()
    {
        // Authorization middleware (if the user does not have appropriate permissions, do a not currently logged in error)
        $this->middleware(['permission:edit users', 'verified'])->only(['edit', 'update']);
        $this->middleware(['permission:add users'])->only(['create', 'store']);
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

    // View a specific user
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    // Modifies the user
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    // Performs an update to the selected user
    public function update(User $user)
    {
        $this->authorize('update', $user);
        return redirect("users.$user->id");
    }

    // Suspend the selected user
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        return redirect("users.$user->id");
    }

}
