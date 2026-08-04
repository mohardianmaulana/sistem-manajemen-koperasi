<?php

namespace Modules\Simpanan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TolakPencairanRequest extends FormRequest
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
            'catatan.required' => 'Catatan penolakan wajib diisi.',
            'catatan.max' => 'Catatan maksimal 255 karakter.',
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
