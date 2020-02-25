<?php

namespace App\Http\Controllers;

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
        sleep(4);
        if (isset($request->code) && is_int($request->code)) {
            return response()->json([
                'message' => 'Code is invalid!',
            ], 400);
        }

//        if (!Msg91::verify(ltrim(backpack_user()->phone), $request->code)) {
        if ($request->code != 1111) {
            return response()->json([
                'message' => 'Incorrect code!',
            ], 400);
        }

        return response()->json([
            'message' => 'Your Phone is verified'
        ], 200);
    }
}
