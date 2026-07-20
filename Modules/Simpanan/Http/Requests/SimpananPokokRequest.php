<?php

namespace Modules\Simpanan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SimpananPokokRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->isMethod('post')) {

            return [

                'id_anggota' => 'required|exists:users,id',

                'nilai' => 'required|numeric|min:1',

                'tanggal' => 'required|date',

            ];
        }

        return [

            'nilai' => 'sometimes|numeric|min:1',

            'tanggal' => 'sometimes|date',

            'status' => 'sometimes|in:pending,selesai,tidak berhasil',

            'bukti' => 'sometimes|file|mimes:jpg,jpeg,png|max:2048',

        ];
    }
}
