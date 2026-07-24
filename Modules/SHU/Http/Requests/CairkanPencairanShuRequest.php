<?php

namespace Modules\SHU\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CairkanPencairanShuRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
     public function rules()
    {
        return [
            'keterangan' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    public function messages()
    {
        return [
            'keterangan.required' => 'Alasan penolakan wajib diisi.',
            'keterangan.max' => 'Alasan penolakan maksimal 255 karakter.',
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
