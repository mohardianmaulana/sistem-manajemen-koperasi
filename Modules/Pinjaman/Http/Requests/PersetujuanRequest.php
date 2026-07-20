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

    public function messages()
    {
        return [
            'id_pengajuan.required' => 'Data pengajuan pinjaman wajib dipilih.',
            'id_pengajuan.exists' => 'Data pengajuan pinjaman tidak ditemukan.',

            'role.required' => 'Role persetujuan wajib diisi.',
            'role.in' => 'Role persetujuan tidak valid.',

            'disetujui_oleh.exists' => 'Data pengguna yang menyetujui tidak ditemukan.',

            'status.required' => 'Status persetujuan wajib dipilih.',
            'status.in' => 'Status persetujuan tidak valid.',

            'tanggal_disetujui.date' => 'Format tanggal persetujuan tidak valid.',

            'catatan.string' => 'Catatan harus berupa teks.',
            'catatan.max' => 'Catatan maksimal terdiri dari 200 karakter.',
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
