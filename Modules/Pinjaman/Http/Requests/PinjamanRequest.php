<?php

namespace Modules\Pinjaman\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PinjamanRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id_pengajuan' => 'required|exists:pengajuan_pinjaman,id',
            'tanggal_disetujui' => 'required|date',
            'jumlah_disetujui' => 'required|numeric|min:0',
            'jumlah_bunga' => 'required|numeric|min:0',
            'total_pinjaman' => 'required|numeric|min:0',
            'status_pinjaman' => 'required|in:belum_aktif,aktif,selesai',
        ];
    }

    public function messages()
    {
        return [
            'id_pengajuan.required' => 'Data pengajuan pinjaman wajib dipilih.',
            'id_pengajuan.exists' => 'Data pengajuan pinjaman tidak ditemukan.',

            'tanggal_disetujui.required' => 'Tanggal persetujuan wajib diisi.',
            'tanggal_disetujui.date' => 'Format tanggal persetujuan tidak valid.',

            'jumlah_disetujui.required' => 'Jumlah pinjaman yang disetujui wajib diisi.',
            'jumlah_disetujui.numeric' => 'Jumlah pinjaman yang disetujui harus berupa angka.',
            'jumlah_disetujui.min' => 'Jumlah pinjaman yang disetujui tidak boleh kurang dari 0.',

            'jumlah_bunga.required' => 'Jumlah bunga wajib diisi.',
            'jumlah_bunga.numeric' => 'Jumlah bunga harus berupa angka.',
            'jumlah_bunga.min' => 'Jumlah bunga tidak boleh kurang dari 0.',

            'total_pinjaman.required' => 'Total pinjaman wajib diisi.',
            'total_pinjaman.numeric' => 'Total pinjaman harus berupa angka.',
            'total_pinjaman.min' => 'Total pinjaman tidak boleh kurang dari 0.',

            'status_pinjaman.required' => 'Status pinjaman wajib dipilih.',
            'status_pinjaman.in' => 'Status pinjaman tidak valid.',
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
