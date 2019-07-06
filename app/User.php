<?php

namespace App;

use Laravel\Passport\hasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable;

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
    private static $rules = [
        'name' => 'required|string|max:255|unique:users',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'first_name' => 'required|string|max:128',
        'family_name' => 'required|string|max:128',
        'avatar' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'social' => 'sometimes|required|url',
    ];

    use HasRoles;

    /**
     * Return the profile associated with this user
     * @return App\Profile
     */
    public function profile()
    {
        return $this->hasOne('App\Profile');
    }

    /**
     * Returns all the rules for this model, that is required for validation
     * @return array
     */
    public static function rules()
    {
        return User::$rules;
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
        $profile = Profile::create([
            'user_id' => $user->id,
            'first_name' => $data['first_name'],
            'family_name' => $data['family_name'],
            'avatar' => $avatarName,
            'social' => array_key_exists('social', $data) ? $data['social'] : null,
        ]);

        return $user;
    }
}
