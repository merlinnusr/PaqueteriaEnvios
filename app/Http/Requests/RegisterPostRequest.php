<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterPostRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|email|unique:usuarios_b,correo,' . $this->id . 'id',
            'password' => 'required|confirmed|min:6',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'El nombre es requerido',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El email debe ser valido',
            'email.unique' => 'Ese email ya esta registrado',
            'password.required' => 'El password es obligatorio',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'password.min' => 'La contraseña debe ser minimo de 6 caracteres',

        ];
    }
}
