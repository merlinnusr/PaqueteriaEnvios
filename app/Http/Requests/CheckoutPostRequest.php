<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutPostRequest extends FormRequest
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
            'name_from' => 'required',
            'email_from'=> 'required',
            'phone_from'=> 'required',
            'zipcode_from'=> 'required',
            'estado_from'=> 'required',
            'ciudad_from'=> 'required',
            'street_from'=> 'required',
            'municipio_from'=> 'required',
            'numero_from'=> 'required',
            'numero_int_from'=> 'nullable',
            'colonia_from'=> 'required',
            'name_to'=> 'required',
            'email_to'=> 'required',
            'phone_to'=> 'required',
            'zipcode_to'=> 'required',
            'estado_to'=> 'required',
            'ciudad_to'=> 'required',
            'municipio_to'=> 'required',
            'street_to'=> 'required',
            'numero_to'=> 'required',
            'numero_int_to'=> 'nullable',
            'colonia_to'=> 'required',
            'description'=> 'required',
        ];
    }
}
