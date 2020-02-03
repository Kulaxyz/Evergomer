<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeviceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:1|max:255',
            'type' => 'required|min:1|max:255',
            'installed_by' => 'required|min:1|max:255',
            'serial_number' => 'required|min:1|max:255',
            'IMEI_number' => 'required|min:1|max:255',
            'phone_number' => 'required|regex:~^\(?\+[0-9]{1,3}\)? ?-?[0-9]{1,3} ?-?[0-9]{3,5} ?-?[0-9]{4}( ?-?[0-9]{3})? ?(\w{1,10}\s?\d{1,6})?~',
            'installed_at' => 'required|date',
            'expires_at' => 'required|date',
            'activated_at' => 'required|date',
            'ports' => 'required',
            'installation_photo' => 'required',
            'charging_time' => 'required',
            'hour_cost' => 'required',
        ];
    }
}
