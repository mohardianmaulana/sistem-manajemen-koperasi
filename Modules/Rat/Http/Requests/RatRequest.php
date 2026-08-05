<?php

namespace Modules\Rat\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RatRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (
            $this->isMethod('POST')
        ) {

            return [
                'tahun' => [
                    'required',
                    'digits:4',
                ],

                'tanggal_rat' => [
                    'required',
                    'date',
                ],

                'status' => [
                    'required',
                    'in:belum,selesai',
                ],
            ];
        }

        if (
            $this->isMethod('PUT') ||
            $this->isMethod('PATCH')
        ) {

            return [
                'tahun' => [
                    'required',
                    'digits:4',
                ],

                'tanggal_rat' => [
                    'required',
                    'date',
                ],

                'status' => [
                    'required',
                    'in:belum,selesai',
                ],
            ];
        }

        return [];
    }

    public function messages()
    {
        return [
            'tahun.required' => 'Tahun RAT wajib diisi.',
            'tahun.digits' => 'Tahun RAT harus terdiri dari 4 digit.',

            'tanggal_rat.required' => 'Tanggal RAT wajib diisi.',
            'tanggal_rat.date' => 'Tanggal RAT tidak valid.',

            'status.required' => 'Status RAT wajib dipilih.',
            'status.in' => 'Status RAT tidak valid.',
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
