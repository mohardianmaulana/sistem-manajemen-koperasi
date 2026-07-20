<?php

namespace Modules\Pinjaman\Http\Controllers\web;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Pinjaman\Services\PengajuanPinjamanService;
use Modules\Pinjaman\Services\PinjamanService;
use Modules\Pinjaman\Services\SkemaPinjamanService;

class PinjamanController extends Controller
{
    private PinjamanService $pinjamanService;
    private SkemaPinjamanService $skemaPinjamanService;
    private PengajuanPinjamanService $pengajuanPinjamanService;

    public function __construct(PinjamanService $pinjamanService, SkemaPinjamanService $skemaPinjamanService, PengajuanPinjamanService $pengajuanPinjamanService)
    {
        $this->pinjamanService = $pinjamanService;
        $this->skemaPinjamanService = $skemaPinjamanService;
        $this->pengajuanPinjamanService = $pengajuanPinjamanService;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $status = $request->status_pinjaman;
        $skema = $request->id_skema_pinjaman;

        $pinjaman = $this->pinjamanService
            ->monitoring($status, $skema);

        $skemaPinjaman = $this->skemaPinjamanService
            ->getAll(['*']);

        $dashboard = [
            'totalAktif' => $pinjaman
                ->where('status_pinjaman','aktif')
                ->count(),

            'totalNominal' => $pinjaman
                ->where('status_pinjaman','aktif')
                ->sum('total_pinjaman'),

            'jatuhTempo' => $pinjaman->filter(function ($item){
                return $item->angsuran
                        ->where('status_bayar','belum_bayar')
                        ->whereBetween(
                            'tanggal_jatuh_tempo',
                            [
                                now()->startOfMonth(),
                                now()->endOfMonth()
                            ]
                        )->count() > 0;
            })->count(),

            'gagalDebet' => $pinjaman->filter(function ($item){
                return $item->angsuran
                        ->where('status_bayar','gagal_debet')
                        ->count() > 0;
            })->count(),
        ];

        return view(
            'pinjaman::pinjaman.index',
            compact('pinjaman', 'dashboard', 'skemaPinjaman')
        );
    }

    public function indexAnggota()
    {
        $fields = ['*'];
        $pinjaman = $this->pinjamanService->getByAnggota($fields);
        $pengajuanPinjaman = $this->pengajuanPinjamanService->getByAnggota($fields);

        return view('pinjaman::pinjaman.indexAnggota', compact('pinjaman', 'pengajuanPinjaman'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        try {
            $fields = ['*'];
            $pinjaman = $this->pinjamanService->getById($fields, $id);
            return response()->json([
                'success' => 'true',
                'data' => $pinjaman,
            ]);            
        } catch (ModelNotFoundException) {
            return response()->json([
                'success' => 'false',
                'message' => 'Data pinjaman tidak ditemukan',
            ]);
        }
    }
}
