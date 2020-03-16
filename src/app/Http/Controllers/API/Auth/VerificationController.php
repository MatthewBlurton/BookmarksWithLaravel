<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Auth\Events\Verified;

use App\User;

class VerificationController extends Controller
{
    use VerifiesEmails;
    public function __construct()
    {
        $this->middleware('auth:api')->only('resend');
        $this->middleware('throttle:6,1');
    }

    public function verify(Request $request)
    {
        $userID = $request['id'];
        try {
            $user = User::findOrFail($userID);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $mnfe) {
            return response()->json(['verified' => false,
                'message' => $mnfe->getMessage(),], 400);
        }

        $date = date('Y-m-d g:i:s');

        $user->email_verified_at = $date; // enables the "email_verified_at" field of that user by mimicing the must verify email function

        $user->save();

        return response()->json(['verified' => true,
            'message' => 'Your account is now verified'], 200);
    }

    /**
     * Resend an email verification to the user
     *
     * @param \Illuminate\Http\Request $request
     */
    public function resend(Request $request)
    {
        $user = auth()->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json(['resent' => false,
                'message' => 'User has already verified their email',], 409);
        }

        try {
            $user->sendApiEmailVerificationNotification();
        } catch (\Exception $e) {
            return response()->json(['resent' => false,
                'message' => $e->getMessage()], 500);
        }
        return response()->json(['resent' => true], 200);
    }
}
