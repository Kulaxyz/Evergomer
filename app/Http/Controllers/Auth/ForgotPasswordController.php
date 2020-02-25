<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Laravel\Msg91\Facade as Msg91;

class ForgotPasswordController extends \Backpack\CRUD\app\Http\Controllers\Auth\ForgotPasswordController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

//    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        $this->data['title'] = trans('backpack::base.reset_password'); // set the page title

        return view(backpack_view('auth.passwords.phone', $this->data));
    }

    public function sendResetLinkOtp(Request $request)
    {
        $user = isset($request->phone) ? User::where('phone', $request->phone)->first() : null;

        if (!$user) {
            return redirect()->back()->withErrors(['phone' => 'There is no user with this phone number']);
        }
        Msg91::otp(ltrim($user->phone));

        return redirect()->route('backpack.auth.password.reset.token');
    }
}
