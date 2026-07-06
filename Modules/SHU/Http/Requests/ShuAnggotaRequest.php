<?php

namespace Modules\SHU\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShuAnggotaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tahun' => 'required|digits:4'
        ];
    }

    public function messages()
    {
        return [
            'tahun.required' => 'Tahun wajib diisi.',
            'tahun.digits'   => 'Tahun harus terdiri dari 4 digit.'
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
