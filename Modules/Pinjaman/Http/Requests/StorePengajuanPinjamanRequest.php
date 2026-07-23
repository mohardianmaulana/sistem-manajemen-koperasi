<?php

namespace Modules\Pinjaman\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Pinjaman\Entities\SkemaPinjaman;

class StorePengajuanPinjamanRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $skema = SkemaPinjaman::find(
            $this->id_skema_pinjaman
        );

        return [
            'id_skema_pinjaman' => 'required|exists:skema_pinjaman,id',
            'tanggal_pengajuan' => 'required|date',
            'jumlah_pengajuan' => 'required|numeric|min:0|gte:'.$skema->min_nominal.'|lte:'.$skema->max_nominal,
            'lama_angsuran' => 'required|numeric|min:0|gte:'.$skema->min_tenor.'|lte:'.$skema->max_tenor,
            'no_hp' => 'required|string|regex:/^[0-9]+$/|min:11|max:15',
            'no_ktp' => 'required|string|digits:16',
            'no_rekening' => 'required|string|digits_between:10,16',
            'alamat' => 'required|string',
            'nama_istri_suami' => 'required|string',
            'jaminan' => 'nullable|array',
            'jaminan.*.id_jaminan' => 'required_with:jaminan|exists:jaminan,id',
            'jaminan.*.file' => 'required_with:jaminan|file|mimes:pdf|max:10048',
        ];
    }

    public function messages()
    {
        return [
            'id_skema_pinjaman.required' => 'Skema pinjaman wajib dipilih.',
            'id_skema_pinjaman.exists' => 'Skema pinjaman yang dipilih tidak valid.',

            'tanggal_pengajuan.required' => 'Tanggal pengajuan wajib diisi.',
            'tanggal_pengajuan.date' => 'Format tanggal pengajuan tidak valid.',

            'jumlah_pengajuan.required' => 'Jumlah pengajuan wajib diisi.',
            'jumlah_pengajuan.numeric' => 'Jumlah pengajuan harus berupa angka.',
            'jumlah_pengajuan.min' => 'Jumlah pengajuan tidak boleh kurang dari 0.',
            'jumlah_pengajuan.gte' => 'Jumlah pengajuan tidak boleh kurang dari nominal minimum pada skema pinjaman.',
            'jumlah_pengajuan.lte' => 'Jumlah pengajuan tidak boleh melebihi nominal maksimum pada skema pinjaman.',

            'lama_angsuran.required' => 'Lama angsuran wajib diisi.',
            'lama_angsuran.numeric' => 'Lama angsuran harus berupa angka.',
            'lama_angsuran.min' => 'Lama angsuran tidak boleh kurang dari 0.',
            'lama_angsuran.gte' => 'Lama angsuran tidak boleh kurang dari tenor minimum pada skema pinjaman.',
            'lama_angsuran.lte' => 'Lama angsuran tidak boleh melebihi tenor maksimum pada skema pinjaman.',

            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.string' => 'Nomor HP harus berupa teks.',
            'no_hp.regex' => 'Nomor HP hanya boleh berisi angka.',
            'no_hp.min' => 'Nomor HP minimal terdiri dari 11 digit.',
            'no_hp.max' => 'Nomor HP maksimal terdiri dari 15 digit.',

            'no_ktp.required' => 'Nomor KTP wajib diisi.',
            'no_ktp.string' => 'Nomor KTP harus berupa teks.',
            'no_ktp.digits' => 'Nomor KTP harus terdiri dari 16 digit.',

            'no_rekening.required' => 'Nomor rekening wajib diisi.',
            'no_rekening.string' => 'Nomor rekening harus berupa teks.',
            'no_rekening.digits_between' => 'Nomor rekening harus terdiri dari 10 sampai 16 digit.',

            'alamat.required' => 'Alamat wajib diisi.',
            'alamat.string' => 'Alamat harus berupa teks.',

            'nama_istri_suami.required' => 'Nama istri/suami wajib diisi.',
            'nama_istri_suami.string' => 'Nama istri/suami harus berupa teks.',

            'jaminan.array' => 'Data jaminan tidak valid.',

            'jaminan.*.id_jaminan.required_with' => 'Jenis jaminan wajib dipilih.',
            'jaminan.*.id_jaminan.exists' => 'Jenis jaminan yang dipilih tidak ditemukan.',

            'jaminan.*.file.required_with' => 'File jaminan wajib diunggah.',
            'jaminan.*.file.file' => 'Dokumen jaminan harus berupa file.',
            'jaminan.*.file.mimes' => 'Dokumen jaminan harus berformat PDF.',
            'jaminan.*.file.max' => 'Ukuran dokumen jaminan maksimal 2 MB.',
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
