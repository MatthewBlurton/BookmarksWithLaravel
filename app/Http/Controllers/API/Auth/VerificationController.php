<?php

namespace App\Http\Controllers\API\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;

class VerificationController extends Controller
{
    use VerifiesEmails;

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

        return response()->json(['verified' => true], 200);
    }

    public function resend(Request $request)
    {
        $userID = $request['id'];
        try {
            $user = User::findOrFail($userID);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $mnfe) {
            return response()->json(['resent' => false,
                'message' => $mnfe->getMessage(),], 400);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['resent' => false,
                'message' => 'User has already verified their email',], 422);
        }

        $user->sendEmailVerificationNotification();
        return response()->json(['resent' => true], 200);

        // if ($request->user()->hasVerifiedEmail()) {
        //     return respones()->json([
        //         'resent' => false,
        //         'message' => 'User has already verified their email',], 422);
        // }

        // $request->user()->sendEmailVerificationNotification();
        // return response()->json(['resent' => true], 200);
    }
}
