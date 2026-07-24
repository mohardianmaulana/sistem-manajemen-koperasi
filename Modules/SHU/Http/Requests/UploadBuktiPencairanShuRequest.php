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
                'file',
                'mimes:jpg,jpeg,png,',
                'max:2048', // 2 MB
            ],
        ];
    }

    public function messages()
    {
        return [
            'bukti.required' => 'Bukti transfer wajib diunggah.',
            'bukti.file'     => 'File yang diunggah tidak valid.',
            'bukti.mimes'    => 'Bukti transfer harus berupa JPG, JPEG, PNG atau PDF.',
            'bukti.max'      => 'Ukuran file maksimal 2 MB.',
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
