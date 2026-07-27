<?php

namespace Modules\Simpanan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [

            'status' => [
                'required',
                'in:selesai,tidak berhasil',
            ],

        ];
    }

    public function messages(): array
    {
        return [

            'status.required' => 'Status pengajuan wajib dipilih.',
            'status.in'       => 'Status pengajuan tidak valid.',

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
