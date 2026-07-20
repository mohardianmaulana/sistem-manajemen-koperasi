<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required|string',
            'username' => 'required|string',
            'email' => 'required|string',
            'unit' => 'required|exists:units,id',
            'role_aktif' => 'required|string',
            'tanda_tangan' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ];
    }

    public function getCredentials()
    {
        return [
            'username' => $this->username,
            'password' => $this->password,
        ];
    }
}
