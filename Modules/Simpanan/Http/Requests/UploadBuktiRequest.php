<?php

namespace Modules\Simpanan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadBuktiRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
   public function rules(): array
    {
        return [

            'bukti' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,',
                'max:2048',
            ],

        ];
    }

    public function messages(): array
    {
        return [

            'bukti.required' => 'Bukti pembayaran wajib diunggah.',
            'bukti.file'     => 'Bukti pembayaran harus berupa file.',
            'bukti.mimes'    => 'Bukti pembayaran harus berformat JPG, JPEG, PNG, .',
            'bukti.max'      => 'Ukuran bukti pembayaran maksimal 2 MB.',

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
