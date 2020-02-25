<?php
namespace App\Traits;

use App\Jobs\SendPhoneVerificationSms;
use App\User;
use Carbon\Carbon;
use Laravel\Msg91\Facade as Msg91;

trait MustVerifyPhoneNumber
{
    public function hasVerifiedPhoneNumber()
    {
        return !is_null($this->phone_verified_at);
    }

    public function markPhoneNumberAsVerified()
    {
        return $this->forceFill([
            'phone_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    public function sendPhoneNumberNotification()
    {
        sleep(4);
//        if (Msg91::otp(ltrim($this->phone))) {
        if (rand(0, 1)) {
            return response()->json([
                'success' => true
            ], 200);
        }
        return response()->json([
            'error' => 'Can`t send'
        ], 500);
    }
}
