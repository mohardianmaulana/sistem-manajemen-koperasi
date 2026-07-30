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
                'bukti' => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:2048',],
            ],

           'PUT', 'PATCH' => [
                'status' => ['required','in:pending,selesai,tidak berhasil',],

                'bukti' => ['sometimes','file', 'mimes:jpg,jpeg,png,','max:2048',],
             ],

            default => [],
        };
    }

    public function messages(): array
    {
        return [

            'nilai.required' => 'Nominal simpanan sukarela wajib diisi.',
            'nilai.numeric'  => 'Nominal simpanan sukarela harus berupa angka.',
            'nilai.min'      => 'Nominal simpanan sukarela minimal Rp1.',

            'periode.required' => 'Periode simpanan sukarela wajib dipilih.',
            'periode.date'     => 'Periode simpanan sukarela tidak valid.',

            'status.required' => 'Status pengajuan wajib dipilih.',
            'status.in'       => 'Status pengajuan tidak valid.',

            'bukti.file'  => 'Bukti transfer harus berupa file.',
            'bukti.mimes' => 'Bukti transfer harus berformat JPG, JPEG, PNG.',
            'bukti.max'   => 'Ukuran bukti transfer maksimal 2 MB.',

        ];
    }
}