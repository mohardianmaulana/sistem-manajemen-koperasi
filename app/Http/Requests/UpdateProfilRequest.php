<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfilRequest extends FormRequest
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
            'email' => 'required|string',
            'nip' => 'required|string',
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
