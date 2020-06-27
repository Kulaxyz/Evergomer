<?php

namespace App\Http\Controllers;

use App\CustomSettings;
use Backpack\Settings\app\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class SettingsController extends Controller
{
    public function change(Request $request)
    {
        $setting = Setting::find($request->id);
        $setting->value = $request->enabled == 'true';
        $setting->save();

        return $setting;
    }

    public function payment_pdf_settings()
    {
        return view(backpack_view('additional.payment_settings'));
    }

    public function update_pdf_settings(Request $request)
    {
        if ($request->hasFile('payment_pdf_logo')) {
            $image = $request->file('payment_pdf_logo');
            $fileName = time() . '.' . $image->getClientOriginalExtension();
            $img = Image::make($image->getRealPath());
            $img->stream(); // <-- Key point
            Storage::disk('local')->put('public/images/settings' . '/' . $fileName, $img, 'public');
            $path = '/storage/images/settings/'.$fileName;
            CustomSettings::set('payment_pdf_logo', $path);
        }

        CustomSettings::set('payment_pdf_prefix', $request->input('prefix'));
        CustomSettings::set('payment_pdf_tax', $request->input('tax'));

        if (!$request->input('promo')) {
            CustomSettings::set('payment_pdf_promo', 0);
        } else {
            CustomSettings::set('payment_pdf_promo', 1);
        }
        return redirect()->back();
    }

}
