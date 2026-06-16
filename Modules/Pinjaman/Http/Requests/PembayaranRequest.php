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
            'jenis_pembayaran' => 'required|in:manual,auto-debet',
            'tanggal_bayar' => 'required|date',
            'jumlah_bayar' => 'required|numeric|min:0',
            'bukti_pembayaran' => 'nullable|file|mimes:jpg,png,jpeg|max:2048',
            'status_pembayaran' => 'nullable|in:verifikasi,ditolak,sukses'
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
