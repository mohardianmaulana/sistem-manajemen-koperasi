<?php

namespace Modules\Pinjaman\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersetujuanRequest extends FormRequest
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
            'role' => 'required|in:bendahara,wadir,ketua',
            'disetujui_oleh' => 'exists:users,id',
            'status' => 'required|in:menunggu,ditolak,disetujui',
            'tanggal_disetujui' => 'date',
            'catatan' => 'string|max:200',
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
