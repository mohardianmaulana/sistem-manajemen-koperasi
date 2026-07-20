<?php

namespace Modules\Pinjaman\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AngsuranRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id_pinjaman' => 'required|exists:pinjaman,id',
            'angsuran_ke' => 'required|numeric',
            'jumlah_angsuran' => 'required|numeric|min:0',
            'tanggal_jatuh_tempo' => 'required|date',
            'status_bayar' => 'required|in:gagal_debet,belum_bayar,lunas'
        ];
    }

    public function messages()
    {
        return [
            'id_pinjaman.required' => 'Data pinjaman wajib dipilih.',
            'id_pinjaman.exists' => 'Data pinjaman tidak ditemukan.',

            'angsuran_ke.required' => 'Nomor angsuran wajib diisi.',
            'angsuran_ke.numeric' => 'Nomor angsuran harus berupa angka.',

            'jumlah_angsuran.required' => 'Jumlah angsuran wajib diisi.',
            'jumlah_angsuran.numeric' => 'Jumlah angsuran harus berupa angka.',
            'jumlah_angsuran.min' => 'Jumlah angsuran tidak boleh kurang dari 0.',

            'tanggal_jatuh_tempo.required' => 'Tanggal jatuh tempo wajib diisi.',
            'tanggal_jatuh_tempo.date' => 'Format tanggal jatuh tempo tidak valid.',

            'status_bayar.required' => 'Status pembayaran wajib dipilih.',
            'status_bayar.in' => 'Status pembayaran tidak valid.',
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
