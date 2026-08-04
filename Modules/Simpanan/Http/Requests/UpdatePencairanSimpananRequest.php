<?php

namespace Modules\Simpanan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePencairanSimpananRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nominal_pencairan' => [
                'required',
                'numeric',
                'min:1000'
            ],

            'alasan' => [
                'nullable',
                'string',
                'max:255'
            ],
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'nominal_pencairan.required' => 'Nominal pencairan wajib diisi.',
            'nominal_pencairan.numeric' => 'Nominal pencairan harus berupa angka.',
            'nominal_pencairan.min' => 'Nominal pencairan minimal Rp1.000.',

            'alasan.string' => 'Alasan harus berupa teks.',
            'alasan.max' => 'Alasan maksimal 255 karakter.',
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
