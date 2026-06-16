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
            'status_bayar' => 'required|in:belum_bayar,lunas'
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
