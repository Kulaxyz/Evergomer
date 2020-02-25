<?php

namespace App\Listeners;

use App\Interfaces\MustVerifyPhoneNumber;
use Illuminate\Auth\Events\Registered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPhoneVerificationNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param Registered $event
     * @return void
     */
    public function handle(Registered $event)
    {
        if ($event->user instanceof MustVerifyPhoneNumber && ! $event->user->hasVerifiedPhoneNumber()) {
            $event->user->sendPhoneNumberNotification();
        }
    }
}
