<?php

namespace App\Http\Controllers\Admin;

use App\Msg;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\Settings\app\Models\Setting;
use Illuminate\Http\Request;

class SmsSettingsCrudController extends CrudController
{
    public function index()
    {
        $msg = Msg::first();
        if (!$msg) {
            $msg = Msg::create([]);
        }
            $settings = Setting::all();
        return view('vendor.backpack.additional.sms_settings', compact('settings', 'msg'));
   }

    public function msg91(Request $request)
    {
        $msg = Msg::first();
        if (!$msg) {
            $msg = Msg::create($request->except('_token'));
            return redirect()->back();
        }
        $msg->update($request->except('_token'));

        return redirect()->back();
    }
}
