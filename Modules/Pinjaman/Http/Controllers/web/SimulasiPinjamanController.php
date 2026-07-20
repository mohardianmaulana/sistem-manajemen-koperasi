<?php

namespace Modules\Pinjaman\Http\Controllers\web;

use Modules\Pinjaman\Entities\SkemaPinjaman;
use Modules\Pinjaman\Http\Requests\SimulasiPinjamanRequest;
use Modules\Pinjaman\Services\SimulasiPinjamanService;
use Modules\Pinjaman\Services\SkemaPinjamanService;

class SimulasiPinjamanController {
    private SimulasiPinjamanService $simulasiPinjamanService;
    private SkemaPinjamanService $skemaPinjamanService;

    public function __construct(SimulasiPinjamanService $simulasiPinjamanService, SkemaPinjamanService $skemaPinjamanService)
    {
        $this->simulasiPinjamanService = $simulasiPinjamanService;
        $this->skemaPinjamanService = $skemaPinjamanService;
    }

    public function index()
    {
        $fields = ['*'];
        $skema_pinjaman = $this->skemaPinjamanService->getAllAktif($fields);
        return view('pinjaman::simulasiPinjaman.index', compact('skema_pinjaman'));
    }

    public function hasil($id_skema)
    {
        $skema = SkemaPinjaman::findOrFail($id_skema);

        return view('pinjaman::simulasiPinjaman.hasil', compact('skema'));
    }

    public function hitung(SimulasiPinjamanRequest $request)
    {
        $nominal = (int) $request->nominal;
        $tenor = (int) $request->tenor;

        $skema = SkemaPinjaman::findOrFail($request->skema_id);

        $hasil = $this->simulasiPinjamanService->hitung_angsuran(
            $nominal, $tenor, $skema
        );

        return response()->json($hasil);
    }
}