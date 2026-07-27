<?php

namespace Modules\Pinjaman\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PembayaranRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id_angsuran' => 'required|exists:angsuran,id',
            'jumlah_bayar' => 'required|numeric|min:0',
            'bukti_pembayaran' => 'nullable|file|mimes:jpg,png,jpeg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'id_angsuran.required' => 'Data angsuran wajib dipilih.',
            'id_angsuran.exists' => 'Data angsuran tidak ditemukan.',

            'jumlah_bayar.required' => 'Jumlah bayar wajib diisi.',
            'jumlah_bayar.numeric' => 'Jumlah bayar harus berupa angka.',
            'jumlah_bayar.min' => 'Jumlah bayar tidak boleh kurang dari 0.',

            'bukti_pembayaran.file' => 'Dokumen jaminan harus berupa file.',
            'bukti_pembayaran.mimes' => 'Dokumen jaminan harus berformat JPG, PNG, JPEG.',
            'bukti_pembayaran.max' => 'Ukuran dokumen jaminan maksimal 2 MB.',
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
