<?php

namespace Modules\Simpanan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CairkanPencairanRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
   public function rules()
    {
        return [
            'bukti_transfer' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048'
            ],
        ];
    }

    public function messages()
    {
        return [
            'bukti_transfer.required' => 'Bukti transfer wajib diunggah.',
            'bukti_transfer.image' => 'File harus berupa gambar.',
            'bukti_transfer.mimes' => 'Format file harus jpg, jpeg, atau png.',
            'bukti_transfer.max' => 'Ukuran file maksimal 2 MB.',
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
