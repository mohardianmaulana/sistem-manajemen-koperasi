<?php

namespace Modules\Unit\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUnitRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    
    public function rules()
    {
        return [
            'nama' => [
                'required',
                'string',
                'max:255',
                'unique:units,nama',
            ],
        ];
    }

    public function messages()
    {
        return [
            'nama.required' => 'Nama unit wajib diisi.',
            'nama.unique'   => 'Nama unit sudah digunakan.',
            'nama.max'      => 'Nama unit maksimal 255 karakter.',
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
