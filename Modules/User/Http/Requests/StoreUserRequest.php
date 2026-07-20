<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

            'name' => 'required|string|max:255',

            'nip' => 'required|string|max:50|unique:users,nip',

            'tempat_lahir' => 'required|string|max:100',

            'tanggal_lahir' => 'required|date',

            'alamat' => 'required|string',

            'no_hp' => 'required|string|max:20',

            'unit' => 'required|exists:units,id',

            'file_sk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',

        ];
    }

     public function messages()
    {
        return [

            'name.required' => 'Nama wajib diisi.',

            'nip.required' => 'NIP wajib diisi.',

            'nip.unique' => 'NIP sudah digunakan.',

            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',

            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',

            'alamat.required' => 'Alamat wajib diisi.',

            'no_hp.required' => 'Nomor HP wajib diisi.',

            'unit.required' => 'Unit kerja wajib dipilih.',

            'file_sk.mimes' => 'File SK harus berupa PDF, JPG, JPEG, atau PNG.',

            'file_sk.max' => 'Ukuran file maksimal 2 MB.',

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
