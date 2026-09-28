<?php

namespace Modules\SHU\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShuAnggotaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

            'periode' => [
                'required',
                'integer',
                'digits:4',
            ],

            'persen_pajak' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

        ];
    }

    public function messages()
    {
        return [

            'periode.required' =>
                'Periode wajib diisi.',

            'periode.integer' =>
                'Periode harus berupa angka.',

            'periode.digits' =>
                'Periode harus terdiri dari 4 digit.',

            'persen_pajak.required' =>
                'Persentase pajak wajib diisi.',

            'persen_pajak.numeric' =>
                'Persentase pajak harus berupa angka.',

            'persen_pajak.min' =>
                'Persentase pajak minimal 0%.',

            'persen_pajak.max' =>
                'Persentase pajak maksimal 100%.',

        ];
    }

    /**
     * Determine if the user is authorized to make the request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}