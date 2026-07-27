<?php

namespace Modules\Simpanan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePengajuanRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
   public function rules(): array
    {
        return [

            'nilai' => [
                'required',
                'numeric',
                'min:1',
            ],

            'periode' => [
                'required',
                'date',
            ],

        ];
    }

    public function messages(): array
    {
        return [

            'nilai.required' => 'Nominal simpanan wajib diisi.',
            'nilai.numeric'  => 'Nominal simpanan harus berupa angka.',
            'nilai.min'      => 'Nominal simpanan minimal Rp1.',

            'periode.required' => 'Periode simpanan wajib diisi.',
            'periode.date'     => 'Format periode tidak valid.',

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
