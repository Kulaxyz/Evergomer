<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentUpdateRequest extends FormRequest
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
        $id = $this->get('id');
        return [
            'user_id' => 'required',
            'amount' => 'required',
            'status' => 'required',
            'custom_id' => 'required|unique:payments,custom_id,'.$id,
        ];
    }
}
