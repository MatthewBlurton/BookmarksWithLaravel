<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

use Auth;
use Validator;

use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Auth\Events\Verified;

use App\User;
use App\Profile;

class PassportController extends Controller
{
    /**
     * Handles Registration Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function Register(Request $request)
    {
        $user;
        $data = [];
        $message = 'Your account has been created! To verify your account check your email.';
        $statusCode = 200;
        // Attempt to validate the request
        try {
            $data = $this->validate($request, User::rules());
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Show all the errors if validation fails
            return response()->json($e->errors(), 400);
        }

        // Attempt to create the user account
        $user = User::createWithProfile($data);

        // Attempt to send an email for the user to verify their email
        try {
            $user->sendApiEmailVerificationNotification();
        } catch (\Exception $e) {
            $message = 'Your account has been created! However we are unable to send an email verification to you. You will have to manually request a new verification email later.';
            $statusCode = 500;
        }

        // Create a token for the api to use for authentication
        $token = $user->createToken('CrossLinkToken')->accessToken;

        // Show the token from the api for later use in authentication
        return response()->json(['token' => $token,
            'message' => $message], $statusCode);
    }

    /**
     * Handles Login Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // assign the credentails from the request
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        // Attempt to login, if successfull, create a token that can be used to access more of the api
        // Otherwise repond with an Unauthorised 401 error
        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('CrossLinkToken')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    /**
     * Handles the Logout Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        // logout the user by revoking the token
        $token = auth()->user()->token();
        $token->revoke();
        return response()->json(['success' => 'Successfully logged out'], 200);
    }

    /**
     * Return the details of the currently authenticated user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function details()
    {
        $user = auth()->user()->toArray();
        $user['profile'] = auth()->user()->profile;
        return response()->json($user, 200);
    }
}
