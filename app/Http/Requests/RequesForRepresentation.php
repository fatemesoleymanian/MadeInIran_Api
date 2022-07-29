<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequesForRepresentation extends FormRequest
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
        return   [
            'full_name' => "bail|required",
            'phone_number' => "bail|required|digits:11",
            'city' => "bail|required|max:50"
        ];
    }
}
