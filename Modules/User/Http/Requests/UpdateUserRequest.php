<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
     public function rules()
    {
        $id = $this->route('id');

        return [

            'name' => 'required|string|max:255',

            'nip' => 'required|string|max:50|unique:users,nip,' . $id,

            'username' => 'required|string|max:50|unique:users,username,' . $id,

            'email' => 'required|email|unique:users,email,' . $id,

            'password' => 'nullable|min:8|confirmed',

            'tempat_lahir' => 'required|string|max:100',

            'tanggal_lahir' => 'required|date',

            'alamat' => 'required|string',

            'no_hp' => 'required|string|max:20',

            'no_rek' => 'required|string|max:50',

            'unit' => 'required|exists:units,id',

            'staff' => 'required|exists:staffs,id',

            'role_aktif' => 'required|exists:roles,name',

            'status' => 'required',

            'file_sk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',

        ];
    }

    public function messages()
    {
        return [

            'name.required' => 'Nama wajib diisi.',

            'nip.required' => 'NIP wajib diisi.',

            'nip.unique' => 'NIP sudah digunakan.',

            'username.required' => 'Username wajib diisi.',

            'username.unique' => 'Username sudah digunakan.',

            'email.required' => 'Email wajib diisi.',

            'email.unique' => 'Email sudah digunakan.',
            
            'role_aktif.required'=>'Hak akses harus diberikan'

        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
