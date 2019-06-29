<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class UsersController extends Controller
{
    public function __construct()
    {
        // Authorization middleware (if the user does not have appropriate permissions, do a not currently logged in error)
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware(['permission:edit users', 'verified'])->only(['edit', 'update']);
        $this->middleware(['permission:add users'])->only(['create', 'store']);
        $this->middleware(['permission:delete users'])->only('destroy');
    }

    // Show all bookmarks
    function index()
    {
        // Show all users if logged in and verified
        if (auth()->check() && auth()->user()->hasVerifiedEmail()) {
            $users = User::orderBy('updated_at', 'DESC')->paginate(8);
        } else { // Otherwise only show 8 users
            $users = User::orderBy('updated_at', 'DESC')->take(8)->get();
        }

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
        // $this->authorize('update', User::class);
    //     return Validator::make($data, [
    //         'name' => ['required', 'string', 'max:255'],
    //         'first_name' => ['required', 'string', 'max:128'],
    //         'family_name' => ['required', 'string', 'max:128'],
    //         'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
    //         'password' => ['required', 'string', 'min:8', 'confirmed'],
    //     ]);
    // }

        $userAttributes = request()->validate([
            'old-password' => 'min|8|confirmed',
            'password' => 'required_with:old-password|string|min:8|confirmed',
        ]);

        $profileAttributes = request()->validate([
            'first_name' => ['required', 'string', 'max:128'],
            'family_name' => ['required', 'string', 'max:128'],
            'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'social' => 'url'
        ]);

        $avatarName = is_null(request()->avatar) ? null
            : $user->id.'_avatar'.time().'.'.request()->avatar->getClientOriginalExtension();

        if (!is_null($avatarName)) {
            request()->avatar->storeAs('avatars', $avatarName);

            $user->profile->avatar = '/storage/avatars/' . $avatarName;
        } else {
            $user->profile->avater = null;
        }
        $user->profile->save();

        return back()->with('success', 'Your account has been successfully updated.');
    }

    // Suspend the selected user
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        return redirect("users.$user->id");
    }

}
