<?php

namespace Modules\SHU\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadBuktiPencairanShuRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'bukti' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],
        ];
    }

    /**
     * Pesan validasi.
     */
    public function messages()
    {
        return [
            'bukti.required' => 'Bukti transfer wajib diunggah.',
            'bukti.image'    => 'File yang diunggah harus berupa gambar.',
            'bukti.mimes'    => 'Bukti transfer harus berformat JPG, JPEG, atau PNG.',
            'bukti.max'      => 'Ukuran bukti transfer maksimal 2 MB.',
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
