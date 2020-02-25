<?php

namespace App\Interfaces;

interface MustVerifyPhoneNumber
{
    /**
     * Determine if the user has verified their email address.
     *
     * @return bool
     */
    public function hasVerifiedPhoneNumber();

    /**
     * Mark the given user's email as verified.
     *
     * @return bool
     */
    public function markPhoneNumberAsVerified();

    /**
     * Send the OPT verification notification.
     *
     * @return void
     */
    public function sendPhoneNumberNotification();
}
