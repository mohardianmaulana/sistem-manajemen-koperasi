<?php

namespace Modules\Pinjaman\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SkemaPinjamanRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nama' => ['required', 'string', 
                        Rule::unique('skema_pinjaman', 'nama')
                        ->ignore($this->route('id'))],
            'min_nominal' => 'required|numeric|min:0',
            'max_nominal' => 'required|numeric|min:0|gte:min_nominal',
            'min_tenor' => 'required|numeric|min:0',
            'max_tenor' => 'required|numeric|min:0|gte:min_tenor',
            'bunga' => 'required|numeric|min:0',
            'jaminan' => 'required|in:tidak,ada',
            'jaminan_ids' => 'required_if:jaminan,ada|array',
            'jaminan_ids.*' => 'exists:jaminan,id',
            'deskripsi' => 'required|string',
            'status' => 'required|in:nonaktif,aktif',
        ];
    }

    public function messages()
    {
        return [
            'nama.required' => 'Nama skema pinjaman wajib diisi.',
            'nama.string' => 'Nama skema pinjaman harus berupa teks.',
            'nama.unique' => 'Nama skema pinjaman sudah digunakan.',

            'min_nominal.required' => 'Nominal minimum wajib diisi.',
            'min_nominal.numeric' => 'Nominal minimum harus berupa angka.',
            'min_nominal.min' => 'Nominal minimum tidak boleh kurang dari 0.',

            'max_nominal.required' => 'Nominal maksimum wajib diisi.',
            'max_nominal.numeric' => 'Nominal maksimum harus berupa angka.',
            'max_nominal.min' => 'Nominal maksimum tidak boleh kurang dari 0.',
            'max_nominal.gte' => 'Nominal maksimum harus lebih besar atau sama dengan nominal minimum.',

            'min_tenor.required' => 'Tenor minimum wajib diisi.',
            'min_tenor.numeric' => 'Tenor minimum harus berupa angka.',
            'min_tenor.min' => 'Tenor minimum tidak boleh kurang dari 0.',

            'max_tenor.required' => 'Tenor maksimum wajib diisi.',
            'max_tenor.numeric' => 'Tenor maksimum harus berupa angka.',
            'max_tenor.min' => 'Tenor maksimum tidak boleh kurang dari 0.',
            'max_tenor.gte' => 'Tenor maksimum harus lebih besar atau sama dengan tenor minimum.',

            'bunga.required' => 'Bunga pinjaman wajib diisi.',
            'bunga.numeric' => 'Bunga pinjaman harus berupa angka.',
            'bunga.min' => 'Bunga pinjaman tidak boleh kurang dari 0.',

            'jaminan.required' => 'Jenis jaminan wajib dipilih.',
            'jaminan.in' => 'Jenis jaminan tidak valid.',

            'jaminan_ids.required_if' => 'Minimal satu jaminan harus dipilih jika pinjaman memerlukan jaminan.',
            'jaminan_ids.array' => 'Data jaminan tidak valid.',
            'jaminan_ids.*.exists' => 'Jaminan yang dipilih tidak ditemukan.',

            'deskripsi.required' => 'Deskripsi skema pinjaman wajib diisi.',
            'deskripsi.string' => 'Deskripsi harus berupa teks.',

            'status.required' => 'Status skema pinjaman wajib dipilih.',
            'status.in' => 'Status skema pinjaman tidak valid.',
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
