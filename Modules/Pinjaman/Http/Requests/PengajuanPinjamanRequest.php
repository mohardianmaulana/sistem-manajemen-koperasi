<?php

namespace Modules\Pinjaman\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PengajuanPinjamanRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id_anggota' => 'required|exists:users,id',
            'id_skema_pinjaman' => 'required|exists:skema_pinjaman,id',
            'tanggal_pengajuan' => 'required|date',
            'jumlah_pengajuan' => 'required|numeric|min:0',
            'lama_angsuran' => 'required|numeric|min:0',
            'status_pengajuan' => 'required|in:menunggu,persetujuan_awal,disetujui,ditolak',
            'no_hp' => 'required|string|regex:/^[0-9]+$/|min:11|max:15',
            'no_ktp' => 'required|string|digits:16',
            'no_rekening' => 'required|string|digits_between:10,16',
            'alamat' => 'required|string',
            'nama_istri_suami' => 'required|string',
            'path_form_pinjaman' => 'file|mimes:pdf|max:2048',
            'path_dokumen' => 'file|mimes:pdf|max:2048',
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
