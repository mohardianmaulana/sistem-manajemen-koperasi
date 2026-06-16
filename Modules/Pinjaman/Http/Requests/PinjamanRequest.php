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
            'id_pengajuan' => 'required|exists:pengajuan_pinjaman, id',
            'tanggal_disetujui' => 'required|date',
            'jumlah_disetujui' => 'required|numeric|min:0',
            'jumlah_bunga' => 'required|numeric|min:0',
            'total_pinjaman' => 'required|numeric|min:0',
            'status_pinjaman' => 'required|in:belum_aktif,aktif,selesai',
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
