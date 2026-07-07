<?php

namespace Modules\SHU\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShuKoperasiRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
            return match ($this->method()) {

            'POST' => [

                'tahun' => ['required','digits:4','integer','unique:sisa_hasil_usaha,tahun',],

                'dana_cadangan' => ['required','numeric','min:0',],

                'jasa_pengurus' => ['required','numeric','min:0',],

                'dana_sosial' => ['required','numeric','min:0',],

            ],

            'PUT', 'PATCH' => [

                'dana_cadangan' => ['required','numeric','min:0',],

                'jasa_pengurus' => ['required','numeric','min:0',],

                'dana_sosial' => ['required','numeric','min:0',],

            ],

            default => [],

        };
        
    }

    public function messages()
    {
        return [

            'tahun.required' => 'Tahun wajib diisi.',
            'tahun.digits'   => 'Tahun harus terdiri dari 4 digit.',
            'tahun.unique'   => 'Data SHU untuk tahun tersebut sudah ada.',

            'dana_cadangan.required' => 'Dana cadangan wajib diisi.',
            'dana_cadangan.numeric'  => 'Dana cadangan harus berupa angka.',
            'dana_cadangan.min'      => 'Dana cadangan tidak boleh kurang dari 0.',

            'jasa_pengurus.required' => 'Jasa pengurus wajib diisi.',
            'jasa_pengurus.numeric'  => 'Jasa pengurus harus berupa angka.',
            'jasa_pengurus.min'      => 'Jasa pengurus tidak boleh kurang dari 0.',

            'dana_sosial.required' => 'Dana sosial wajib diisi.',
            'dana_sosial.numeric'  => 'Dana sosial harus berupa angka.',
            'dana_sosial.min'      => 'Dana sosial tidak boleh kurang dari 0.',

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
