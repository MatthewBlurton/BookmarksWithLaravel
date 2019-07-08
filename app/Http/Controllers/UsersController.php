<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\User;

class UsersController extends Controller
{
    public function __construct()
    {
        // Authorization middleware (if the user does not have appropriate permissions, do a not currently logged in error)
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware(['permission:edit users', 'verified'])->only(['edit', 'update']);
        // $this->middleware(['can,create:App\User'])->only(['create', 'store']);
        $this->middleware(['permission:suspend user'])->only('suspend');
    }

    // Show all bookmarks
    function index()
    {
        // Show all users if logged in and verified
        if (auth()->check() && auth()->user()->hasVerifiedEmail()
            && !auth()->user()->hasRole('suspended')) {
            $users = User::orderBy('updated_at', 'DESC')->paginate(8);
        } else { // Otherwise only show 8 users
            $users = User::orderBy('updated_at', 'DESC')->take(8)->get();
        }

        return view (
            'users.index',
            compact('users')
        );
    }

    /**
     *
     */
    public function create()
    {
        return view('users/create');
    }

    /**
     * Store a new user
     */
    public function store(Response $response)
    {
        $response->validate(User::rules());
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

    // Performs an update to the selected user's password
    public function update(User $user)
    {
        $this->authorize('update', $user);

        $attributes = request()->validate([
            'old-password' => 'required',
            'password' => 'required:old-password|string|min:8|confirmed',
        ]);

        // Check if the old password matches the current password
        $currentPassword = auth()->user()->password;

        if (Hash::check($attributes['old-password'], $currentPassword))
        {
            // Hash the new password
            $attributes['password'] = Hash::make($attributes['password']);
        }
        else
        {
            return redirect()->back()->withErrors(['old-password', 'password does not match existing password']);
        }

        // Save the new password to the user
        $user->update($attributes);
        $user->save();

        // Return with success
        return back()->with('success', 'Your user settings has been successfully updated.');
    }

    // Performs an update to the selected user's profile
    public function updateProfile(User $user)
    {
        $this->authorize('updateProfile', $user);
        $attributes = request()->validate([
            'first_name' => ['required', 'string', 'max:128'],
            'family_name' => ['required', 'string', 'max:128'],
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'social' => 'url'
        ]);

        $avatarName = is_null(request()->avatar) ? null
            : $user->id.'_avatar'.time().'.'.request()->avatar->getClientOriginalExtension();

        $user->profile->update($attributes);

        if (!is_null($avatarName))
        {
            request()->avatar->storeAs('avatars', $avatarName);
            $user->profile->avatar = '/storage/avatars/' . $avatarName;
        }
        $user->profile->save();
        $user->touch();

        return back()->with('success', 'Your profile settings has been successfully updated.');
    }

    // Used to force change the user account (unused)
    public function updateElevated(User $user)
    {
        $this->authorize('updateElevated', $user);

        $credentials = ['email' => $user->email];
        $response = Password::sendResetLink($credentials, function (Message $message) {
            $message->subject($this->getEmailSubject());
        });

        return 'test';

        switch ($response) {
            case Password::RESET_LINK_SENT:
                return redirect()->back()->with('status', trans($response));
            case Password::INVALID_USER:
                return redirect()->back()->withErrors(['email' => trans($response)]);
        }



        return redirect()->back()->with('success', 'A password reset notification has been sent to the user');
    }

    // Used to assign the role
    public function assignRole(User $user)
    {
        $this->authorize('assignRole', $user);

        $attributes = request()->validate([
            'role' => ['required', 'regex:/^((user)|(user-admin)|(admin))$/i']
        ]);

        $user->removeRole('user');
        $user->removeRole('user-admin');
        $user->removeRole('admin');

        $user->assignRole($attributes['role']);
        $user->save();

        return back();
    }

    // Used to suspend the user
    public function suspend(User $user)
    {
        $this->authorize('suspend', $user);

        if ($user->hasRole('suspended'))
        {
            $user->assignRole('user');
            $user->removeRole('suspended');
            return back();
        }

        $user->removeRole('user');
        $user->removeRole('user-admin');
        $user->removeRole('admin');

        $user->assignRole('suspended');
        $user->save();

        return back();
    }

}
