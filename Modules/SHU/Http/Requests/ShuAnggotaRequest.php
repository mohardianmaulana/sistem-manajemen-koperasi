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

            'periode_awal' => [
                'required',
                'date',
            ],

            'periode_akhir' => [
                'required',
                'date',
                'after_or_equal:periode_awal',
            ],

            'persen_jasa_pengurus' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
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

            'periode_awal.required' =>
                'Periode awal wajib diisi.',

            'periode_awal.date' =>
                'Periode awal tidak valid.',

            'periode_akhir.required' =>
                'Periode akhir wajib diisi.',

            'periode_akhir.date' =>
                'Periode akhir tidak valid.',

            'periode_akhir.after_or_equal' =>
                'Periode akhir harus lebih besar atau sama dengan periode awal.',

            'persen_jasa_pengurus.required' =>
                'Persentase jasa pengurus wajib diisi.',

            'persen_jasa_pengurus.numeric' =>
                'Persentase jasa pengurus harus berupa angka.',

            'persen_jasa_pengurus.min' =>
                'Persentase jasa pengurus minimal 0%.',

            'persen_jasa_pengurus.max' =>
                'Persentase jasa pengurus maksimal 100%.',

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
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
