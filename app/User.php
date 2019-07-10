<?php

namespace App;

use Laravel\Passport\hasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Notifications\API\VerifyEmail as VerifyApiEmail;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable, HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Rules used for validation purposes
     * @var rules
     */
    protected static $rules = [
        'name' => 'required|string|max:255|unique:users',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'first_name' => 'required|string|max:128',
        'family_name' => 'required|string|max:128',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'social' => 'nullable|url',
    ];

    /**
     * Returns all the rules for this model, that is required for validation
     * @return array
     */
    public static function rules()
    {
        return User::$rules;
    }

    /**
     * Return the profile associated with this user
     * @return App\Profile
     */
    public function profile()
    {
        return $this->hasOne('App\Profile');
    }

    /**
     * Return all the bookmarks associated with this user
     * @return App\Bookmark
     */
    public function bookmarks()
    {
        return $this->hasMany('App\Bookmark');
    }

    /**
     * Return only the bookmarks allowed to be returned based on the provided user
     * If the user owns the bookmarks or the user has a role of admin or higher then return all the bookmarks
     * If the user is a guest or suspended then only return 5 of the latest public bookmarks
     *
     * @param \App\User
     * @return \App\Bookmark
     */
    public function bookmarksFiltered(?User $user)
    {
        if ($user && $user->id === $this->id) {
            return Bookmark::where('user_id', $this->id)->orderBy('updated_at', 'DESC')->paginate(5);
        }

        if ($user && $user->hasVerifiedEmail() && !$user->hasRole('suspended')) {
            return $user->hasPermissionTo('access all bookmarks')
                ? Bookmark::where('user_id', $this->id)->orderBy('updated_at', 'DESC')->paginate(5)
                : Bookmark::where('user_id', $this->id)->where('is_public', true)
                    ->orderBy('updated_at', 'DESC')->paginate(5);
        }

        return Bookmark::where('user_id', $this->id)->where('is_public', true)->orderBy('updated_at', 'DESC')->take(5)->get();

        // // Paginate check: is the user a guest, has the user verified their email, and is the user not suspended?
        // if ($user && $user->hasVerifiedEmail() && !$user->hasRole('suspended'))
        // {
        //     // Check if the user has permission to access all bookmarks, then return all the bookmarks no matter if they are private or public
        //     // Otherwise only return the bookmark if any of the following conditions are true
        //     // 1. The bookmark is owned by the currently logged in user
        //     // 2. The bookmark is set to public
        //     return $user->hasPermissionTo('access all bookmarks')
        //         ? Bookmark::orderBy('updated_at', 'DESC')->paginate(5)
        //         : Bookmark::where('user_id', $user->id)->orWhere('is_public', true)
        //             ->orderBy('updated_at', 'DESC')->paginate(5);
        // }
        // else { // If the paginate check fails, only grab 10 of the latest PUBLIC bookmarks.
        //     return Bookmark::where('is_public', true)->orderBy('updated_at', 'DESC')
        //         ->take(5)->get();
        // }
    }


    /**
     * Used to create a profile along with the user.
     * Requires: name, email, password, first_name, last_name
     * Optional: avatar, social
     * @param array $data
     * @return App\User
     */
    public static function createWithProfile(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole('user');

        $avatarName = array_key_exists('avatar', $data) ? $user->id . '_avatar' . time() . '.' . $data['avatar']->getClientOriginalExtension()
            : null;

        // If the avatarName is not null, store it!
        if (isset($avatarName)) {
            $data['avatar']->storeAs('avatars', $avatarName);
            $avatarName = '/storage/avatars/' . $avatarName;
        }

        // mass assign attributes to profile
        $profile = new Profile();
        $profile->fill([
            'first_name' => $data['first_name'],
            'family_name' => $data['family_name'],
            'avatar' => $avatarName,
            'social' => array_key_exists('social', $data) ? $data['social'] : null,
        ]);

        // assign user id to the currently created user
        $profile->user_id = $user->id;
        $profile->save();

        return $user;
    }

    public function sendApiEmailVerificationNotification()
    {
        $this->notify(new VerifyApiEmail); // Notify the email verification api if this function is called
    }
}
