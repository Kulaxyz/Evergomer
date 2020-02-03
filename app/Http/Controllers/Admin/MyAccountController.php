<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Prologue\Alerts\Facades\Alert;
use Backpack\CRUD\app\Http\Requests\AccountInfoRequest;
use Backpack\CRUD\app\Http\Requests\ChangePasswordRequest;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

class MyAccountController extends \Backpack\CRUD\app\Http\Controllers\MyAccountController
{
    protected $data = [];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Show the user a form to change his personal information & password.
     */
    public function getAccountInfoForm()
    {
        $this->data['title'] = trans('backpack::base.my_account');
        $this->data['user'] = $this->guard()->user();

        return view(backpack_view('my_account'), $this->data);
    }

    /**
     * Save the modified personal information for a user.
     * @param AccountInfoRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAccountInfoForm(AccountInfoRequest $request)
    {

        $image = $request->file('avatar');
        $fileName = time() . '.' . $image->getClientOriginalExtension();
        $img = Image::make($image->getRealPath());
        $img->stream(); // <-- Key point
        Storage::disk('local')->put('public/images/avatars' . '/' . $fileName, $img, 'public');

        $arr = $request->except(['_token', 'avatar']);
        $arr = array_merge($arr, ['avatar' => '/storage/images/avatars/'.$fileName]);
        $result = $this->guard()->user()->update($arr);

        if ($result) {
            Alert::success(trans('backpack::base.account_updated'))->flash();
        } else {
            Alert::error(trans('backpack::base.error_saving'))->flash();
        }

        return redirect()->back();
    }

    /**
     * Save the new password for a user.
     */
    public function postChangePasswordForm(ChangePasswordRequest $request)
    {
        $user = $this->guard()->user();
        $user->password = Hash::make($request->new_password);

        if ($user->save()) {
            Alert::success(trans('backpack::base.account_updated'))->flash();
        } else {
            Alert::error(trans('backpack::base.error_saving'))->flash();
        }

        return redirect()->back();
    }

    /**
     * Get the guard to be used for account manipulation.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return backpack_auth();
    }
}
