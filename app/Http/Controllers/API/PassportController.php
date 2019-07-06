<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

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
        // Attempt to validate the request
        try {
            $data = $this->validate($request, User::rules());
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Show all the errors if validation fails
            return response()->json($e->errors(), 400);
        }

        try {
            $user = User::createWithProfile($data);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        // Create a token for the api to use for authentication
        $token = $user->createToken('CrossLinkToken')->accessToken;

        // Show the token from the api for later use in authentication
        return response()->json(['token' => $token], 201);
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function details()
    {
        $user = auth()->user()->toArray();
        $user['profile'] = auth()->user()->profile;
        return response()->json(['user' => $user], 200);
    }
}
