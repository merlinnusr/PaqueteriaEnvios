<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PackageDetailPostRequest extends FormRequest
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
            'zipcode_from' => 'required|max:5|min:5',
            'zipcode_to' => 'required|max:5|min:5',
            'width' => 'required|numeric',
            'length' => 'required|numeric',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'weight_real' => 'required|numeric',
            'description' => 'required|max:255'
        ];
    }
}
