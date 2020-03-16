<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\User;
use App\Bookmark;

class UsersController extends Controller
{
    public function __construct()
    {
        // Authorization middleware (if the user does not have appropriate permissions, do a not currently logged in error)
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware(['verified', 'can:update,user'])->only(['edit', 'update']);
        $this->middleware(['can:create,App\User'])->only(['create', 'store']);
        $this->middleware(['can:suspend,user'])->only('suspend');
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
        return view('users.create');
    }

    /**
     * Store a new user
     */
    public function store(Request $request)
    {
        $request->validate(User::rules());
        $user = User::createWithProfile($request->all());

        Route::redirect('users.show', compact('user'));
    }

    // View a specific user
    public function show(User $user)
    {
        $loggedIn = auth()->check() ? auth()->user() : null;
        $bookmarks = $user->bookmarksFiltered($loggedIn);
        return view('users.show', compact('user'), compact('bookmarks'));
    }

    // Modifies the user
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    /**
     * Shows the password change form for the currently logged in user
     */
    public function showChangePasswordForm()
    {
        return view('users.changePassword');
    }

    /**
     * Attempts to change the password by
     * 1. Verify that the old password matches the new password
     * 2. New password matches confirmed new password
     *
     * @param \Illuminate\Http\Request $request
     */
    public function changePassword(Request $request)
    {
        // Check if the old password doesn't match the current user's password
        if (!Hash::check($request->old_password, auth()->user()->password)) {
            // If so redirect back to the previous page with errors
            return redirect()->back()->withErrors(['old_password' => 'password doesn\'t match the currently logged in user']);
        }
        // Attempt to validate the password
        $request->validate(['password' => 'required|confirmed|min:8']);

        // Update the password to the new hashed password
        auth()->user()->password = Hash::make($request->password);
        auth()->user()->save();
        return redirect()->back()->with(['success' => 'Password successfullychanged!'])->withInput();
    }

    /**
     * Updates both the user's account, and the user's profile
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\User $user
     */
    public function update(Request $request, User $user)
    {
        // Check if the user is able to update the password, username and email
        $userAttributes = [];
        $profileAttributes = [];

        if (auth()->user()->can('updateElevate', $user)) {
            $rules = [];

            // Since name, email, and password are all optional only add rules for validation
            // If the resource is the same, do not apply the rules
            if ($user->name !== $request->name) {
                $rules['name'] = User::rules()['name'];
            }

            if ($user->email !== $request->email) {
                $rules['email'] = User::rules()['email'];
            }

            // If no new password was added, do not add a validation rule for passwords
            if (isset($request->password) || isset($request->password_confirmation)) {
                $rules['password'] = User::rules()['password'];
            }
            $request->validate($rules);

            // Manually assign new values based on which rules where created
            $userAttributes['name'] = array_key_exists('name', $rules) ? $request->name : $user->name;
            $userAttributes['email'] = array_key_exists('email', $rules) ? $request->email : $user->email;
            $userAttributes['password'] = array_key_exists('password', $rules) ? Hash::make($request->password) : $user->password;
        }

        // Check if the user is able to update the first name, family name, avatar, and social
        if (auth()->user()->can('updateProfile', $user)) {
            $rules = [
                'first_name' => User::rules()['first_name'],
                'family_name' => User::rules()['family_name'],
                'social' => User::rules()['social'],
            ];

            // Only do the avatar rule if one is selected
            if (isset($request->avatar)) {
                $rules['avatar'] = User::rules()['avatar'];
            }
            $request->validate($rules);

            // If the rule exists for avatar, that means the user wants to update their avatar.
            // Replicate the method of creating avatars from the User model.
            if (array_key_exists('avatar', $rules)) {
                $avatarName = $user->id . '_avatar' . time() . '.' . $request->avatar->getClientOriginalExtension();
                $request->avatar->storeAs('avatars', $avatarName);
                $avatarName = '/storage/avatars/' . $avatarName;
            }

            // Assign profile attributes
            $profileAttributes['first_name'] = $request->first_name;
            $profileAttributes['family_name'] = $request->family_name;
            $profileAttributes['social'] = $request->social;
            $profileAttributes['avatar'] = isset($avatarName) ? $avatarName : $user->profile->avatar;
        }

        // If all validation succeeds update both the user and the profile
        $user->update($userAttributes);
        $user->save();
        $user->profile->update($profileAttributes);
        $user->profile->save();

        // If successfull notify the user that their account has been updated
        return redirect()->back()->with(['success' => 'All form inputs validated successfully!']);
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

        // Remove all roles
        $user->removeRole('user');
        $user->removeRole('user-admin');
        $user->removeRole('admin');

        // Assign and apply the new role
        $user->assignRole($attributes['role']);
        $user->save();

        if (auth()->user()->can('update', $user)) {
            // If the user can still change the account redirect back
            return redirect()->back()->with(['success' => 'The user\'s role has been changed']);
        } else {
            return redirect()->route('users.index', ['success' => 'The user\'s role has been changed.']);
        }

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
