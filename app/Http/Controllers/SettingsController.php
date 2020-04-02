<?php

namespace App\Http\Controllers;

use Backpack\Settings\app\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function change(Request $request)
    {
        $setting = Setting::find($request->id);
        $setting->value = $request->enabled == 'true';
        $setting->save();

        return $setting;
    }
}
