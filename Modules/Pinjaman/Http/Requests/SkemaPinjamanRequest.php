<?php

namespace Modules\Pinjaman\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SkemaPinjamanRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nama' => ['required', 'string', 
                        Rule::unique('skema_pinjaman', 'nama')
                        ->ignore($this->route('id'))],
            'min_nominal' => 'required|numeric|min:0',
            'max_nominal' => 'required|numeric|min:0|gte:min_nominal',
            'min_tenor' => 'required|numeric|min:0',
            'max_tenor' => 'required|numeric|min:0|gte:min_tenor',
            'bunga' => 'required|numeric|min:0',
            'jaminan' => 'required|in:tidak,ada',
            'deskripsi' => 'required|string',
            'status' => 'required|in:nonaktif,aktif',
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
