<?php

namespace Modules\SHU\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePencairanShuRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id_shu_anggota' => [
                'required',
                'exists:shu_anggota,id',
            ],

            'nominal_pengajuan' => [
                'required',
                'numeric',
                'min:1',
            ],
        ];
    }

    public function messages()
    {
        return [
            'id_shu_anggota.required' => 'Data SHU wajib dipilih.',
            'id_shu_anggota.exists' => 'Data SHU tidak ditemukan.',

            'nominal_pengajuan.required' => 'Nominal pencairan wajib diisi.',
            'nominal_pengajuan.numeric' => 'Nominal harus berupa angka.',
            'nominal_pengajuan.min' => 'Nominal minimal Rp1.',
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
