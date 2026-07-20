<?php

namespace Modules\Simpanan\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MasterSimpananSukarelaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return match ($this->method()) {

            'POST' => [
                'nilai'      => ['required', 'numeric', 'min:1'],
                'periode'    => ['required', 'date'],
            ],

           'PUT', 'PATCH' => [
                'status' => ['required','in:pending,selesai,tidak berhasil',],

                'bukti' => ['sometimes','file', 'mimes:jpg,jpeg,png,pdf','max:2048',],
             ],

            default => [],
        };
    }
}