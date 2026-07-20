<?php

namespace Modules\Pinjaman\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Pinjaman\Entities\SkemaPinjaman;

class SimulasiPinjamanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $skema = SkemaPinjaman::find($this->skema_id);

        if (!$skema) {
            return [
                'skema_id' => 'required|exists:skema_pinjaman,id',
            ];
        }

        return [
            'skema_id' => 'required|exists:skema_pinjaman,id',
            'nominal' => [
                'required',
                'numeric',
                'min:' . $skema->min_nominal,
                'max:' . $skema->max_nominal,
            ],
            'tenor' => [
                'required',
                'integer',
                'min:' . $skema->min_tenor,
                'max:' . $skema->max_tenor,
            ],
        ];
    }

    public function messages(): array
    {
        $skema = SkemaPinjaman::find($this->skema_id);

        return [
            'skema_id.required' => 'Skema pinjaman wajib dipilih.',
            'skema_id.exists' => 'Skema pinjaman tidak ditemukan.',

            'nominal.required' => 'Nominal pinjaman wajib diisi.',
            'nominal.numeric' => 'Nominal pinjaman harus berupa angka.',
            'nominal.min' => 'Nominal pinjaman minimal Rp ' . number_format($skema?->min_nominal ?? 0, 0, ',', '.'),
            'nominal.max' => 'Nominal pinjaman maksimal Rp ' . number_format($skema?->max_nominal ?? 0, 0, ',', '.'),

            'tenor.required' => 'Tenor wajib dipilih.',
            'tenor.integer' => 'Tenor harus berupa angka.',
            'tenor.min' => 'Tenor minimal ' . ($skema?->min_tenor ?? 0) . ' bulan.',
            'tenor.max' => 'Tenor maksimal ' . ($skema?->max_tenor ?? 0) . ' bulan.',
        ];
    }
}