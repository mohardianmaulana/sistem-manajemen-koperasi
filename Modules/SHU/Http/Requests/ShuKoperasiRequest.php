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
                'POST' => ['periode_awal' => ['required','date',],
                           'periode_akhir' => ['required','date','after:periode_awal',],
                           'total_shu' => ['required','numeric','min:1',],
                           'persen_jasa_simpanan' => ['required','numeric','min:0','max:100',],
                           'persen_jasa_pinjaman' => ['required','numeric','min:0','max:100',],
                           'persen_dana_cadangan' => ['required','numeric','min:0','max:100',],
                           'persen_jasa_pengurus' => ['required','numeric','min:0','max:100',],
                           'persen_dana_sosial' => ['required','numeric','min:0','max:100',],
                           ],
                'PUT', 'PATCH' => ['periode_awal' => ['required','date',],
                                   'periode_akhir' => ['required','date','after:periode_awal',],
                                   'total_shu' => ['required','numeric','min:1',],
                                   'persen_jasa_simpanan' => ['required','numeric','min:0','max:100',],
                                   'persen_jasa_pinjaman' => ['required','numeric','min:0','max:100',],
                                   'persen_dana_cadangan' => ['required','numeric','min:0','max:100',],
                                   'persen_jasa_pengurus' => ['required','numeric','min:0','max:100',],
                                   'persen_dana_sosial' => ['required','numeric','min:0','max:100',],],

                default => [],
            };
    }

   public function messages()
    {
        return [

            // Periode
            'periode_awal.required' => 'Periode awal wajib diisi.',
            'periode_awal.date'     => 'Format periode awal tidak valid.',

            'periode_akhir.required' => 'Periode akhir wajib diisi.',
            'periode_akhir.date'     => 'Format periode akhir tidak valid.',
            'periode_akhir.after'    => 'Periode akhir harus lebih besar dari periode awal.',

            // Total SHU
            'total_shu.required' => 'Total SHU wajib diisi.',
            'total_shu.numeric'  => 'Total SHU harus berupa angka.',
            'total_shu.min'      => 'Total SHU harus lebih dari 0.',

            // Persentase Jasa Simpanan
            'persen_jasa_simpanan.required' => 'Persentase jasa simpanan wajib diisi.',
            'persen_jasa_simpanan.numeric'  => 'Persentase jasa simpanan harus berupa angka.',
            'persen_jasa_simpanan.min'      => 'Persentase jasa simpanan tidak boleh kurang dari 0%.',
            'persen_jasa_simpanan.max'      => 'Persentase jasa simpanan tidak boleh lebih dari 100%.',

            // Persentase Jasa Pinjaman
            'persen_jasa_pinjaman.required' => 'Persentase jasa pinjaman wajib diisi.',
            'persen_jasa_pinjaman.numeric'  => 'Persentase jasa pinjaman harus berupa angka.',
            'persen_jasa_pinjaman.min'      => 'Persentase jasa pinjaman tidak boleh kurang dari 0%.',
            'persen_jasa_pinjaman.max'      => 'Persentase jasa pinjaman tidak boleh lebih dari 100%.',

            // Persentase Dana Cadangan
            'persen_dana_cadangan.required' => 'Persentase dana cadangan wajib diisi.',
            'persen_dana_cadangan.numeric'  => 'Persentase dana cadangan harus berupa angka.',
            'persen_dana_cadangan.min'      => 'Persentase dana cadangan tidak boleh kurang dari 0%.',
            'persen_dana_cadangan.max'      => 'Persentase dana cadangan tidak boleh lebih dari 100%.',

            // Persentase Jasa Pengurus
            'persen_jasa_pengurus.required' => 'Persentase jasa pengurus wajib diisi.',
            'persen_jasa_pengurus.numeric'  => 'Persentase jasa pengurus harus berupa angka.',
            'persen_jasa_pengurus.min'      => 'Persentase jasa pengurus tidak boleh kurang dari 0%.',
            'persen_jasa_pengurus.max'      => 'Persentase jasa pengurus tidak boleh lebih dari 100%.',

            // Persentase Dana Sosial
            'persen_dana_sosial.required' => 'Persentase dana sosial wajib diisi.',
            'persen_dana_sosial.numeric'  => 'Persentase dana sosial harus berupa angka.',
            'persen_dana_sosial.min'      => 'Persentase dana sosial tidak boleh kurang dari 0%.',
            'persen_dana_sosial.max'      => 'Persentase dana sosial tidak boleh lebih dari 100%.',

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
