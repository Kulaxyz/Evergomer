<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
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
        $rules =  [
            'user_rfid' => 'required|string',
            'device_serial' => 'required|string',
            'port_number' => 'required|numeric',
            'charge_duration' => 'required',
            'charge_power' => 'required',
        ];
        switch ($this->getMethod())
        {
            case 'POST':
                return $rules;
            case 'PUT':
                return [
                    'port_number' => 'required|numeric',
                    'charge_duration' => 'required',
                    'charge_power' => 'required',
                    ];

        }

        return [];
    }
}
