<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Msg91\Facade as Msg91;

class SmsController extends Controller
{
    public function send()
    {
        return backpack_user()->sendPhoneNumberNotification();
    }

    public function verifyOtp(Request $request)
    {
        if (!isset($request->code)) {
            return response()->json([
                'message' => 'Code is invalid!',
            ], 400);
        }

        if (!Msg91::verify(ltrim(backpack_user()->phone), $request->code)) {
//        if ($request->code !== 1111) {
            return response()->json([
                'message' => 'Incorrect code!',
            ], 400);
        }

        backpack_user()->phone_verified_at = Carbon::now();
        backpack_user()->save();

        return response()->json([
            'message' => 'Your Phone is verified'
        ], 200);
    }
}
