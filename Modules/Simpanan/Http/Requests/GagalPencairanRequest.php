<?php

namespace Modules\Simpanan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GagalPencairanRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
     public function rules()
    {
        return [
            'catatan' => [
                'required',
                'string',
                'max:255'
            ],
        ];
    }

    public function messages()
    {
        return [
            'catatan.required' => 'Alasan kegagalan wajib diisi.',
            'catatan.max' => 'Alasan kegagalan maksimal 255 karakter.',
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
