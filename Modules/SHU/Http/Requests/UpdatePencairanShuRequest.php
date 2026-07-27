<?php

namespace Modules\SHU\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePencairanShuRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
     public function rules()
    {
        return [
            'nominal_pengajuan' => [
                'required',
                'numeric',
                'min:1',
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages()
    {
        return [
            'nominal_pengajuan.required' => 'Nominal pengajuan wajib diisi.',
            'nominal_pengajuan.numeric'  => 'Nominal pengajuan harus berupa angka.',
            'nominal_pengajuan.min'      => 'Nominal pengajuan minimal Rp 1.',
        ];
    }

    /**
     * Custom attribute names.
     */
    public function attributes()
    {
        return [
            'nominal_pengajuan' => 'nominal pengajuan',
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
