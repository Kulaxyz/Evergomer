<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use App\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Laravel\Msg91\Facade as Msg91;

class ResetPasswordController extends \Backpack\CRUD\app\Http\Controllers\Auth\ResetPasswordController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

//    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function resetPass(ResetPasswordRequest $request)
    {
        $user = isset($request->phone) ? User::where('phone', $request->phone)->first() : null;
        if (!$user) {
            return redirect()->back()->withErrors(['phone' => 'There is no user with this phone number']);
        }

        if (Msg91::verify(ltrim($user->phone), $request->code)) {
            $this->resetPassword($user, $request->password);
        }
        return redirect()->route('backpack.account.info');
    }
}
