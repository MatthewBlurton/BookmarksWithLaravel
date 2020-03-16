<?php

namespace App\Notifications\API;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;

class VerifyEmail extends VerifyEmailBase
{
    /**
     * Get the verification URL for the given notifiable
     *
     * @param mixed $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'api.verification.verify', Carbon::now()->addMinutes(60),['id' => $notifiable->getKey()]
        ); // Mimic the email endpoint with get request
    }
}
