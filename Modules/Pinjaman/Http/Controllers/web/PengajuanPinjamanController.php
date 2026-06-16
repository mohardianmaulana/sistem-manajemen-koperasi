<?php

namespace Modules\Pinjaman\Http\Controllers\web;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Pinjaman\Entities\SkemaPinjaman;
use Modules\Pinjaman\Http\Requests\PengajuanPinjamanRequest;
use Modules\Pinjaman\Services\SkemaPinjamanService;
use Modules\Pinjaman\Services\PengajuanPinjamanService;
use Modules\Pinjaman\Services\PinjamanService;

class PengajuanPinjamanController extends Controller
{
    private PengajuanPinjamanService $pengajuanPinjamanService;
    private SkemaPinjamanService $skemaPinjamanService;
    private PinjamanService $pinjamanService;

    public function __construct(PengajuanPinjamanService $pengajuanPinjamanService, SkemaPinjamanService $skemaPinjamanService, PinjamanService $pinjamanService)
    {
        $this->pengajuanPinjamanService = $pengajuanPinjamanService;
        $this->skemaPinjamanService = $skemaPinjamanService;
        $this->pinjamanService = $pinjamanService;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $fields = ['*'];
        $pengajuanPinjaman = $this->pengajuanPinjamanService->getAll($fields);
        return view('pinjaman::pengajuanPinjaman.index', compact('pengajuanPinjaman'));
    }

    public function indexAnggota()
    {
        $user_id = Auth::id();
        $pinjamanAktif = $this->pinjamanService->cekPinjamanAktif($user_id);
        // dd($pinjamanAktif);

        $fields = ['*'];
        $skema_pinjaman = $this->skemaPinjamanService->getAll($fields);

        if ($pinjamanAktif) {
            return view(
                'pinjaman::pengajuanPinjaman.indexAnggota',
                [
                    'skema_pinjaman' => $skema_pinjaman,
                    'disablePengajuan' => $pinjamanAktif,
                    'error' => $pinjamanAktif
                        ? 'Anda masih memiliki pinjaman yang belum selesai'
                        : null
                ]
            );
        }

        return view('pinjaman::pengajuanPinjaman.indexAnggota', compact('skema_pinjaman'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($id_skema)
    {
        $user_id = Auth::id();
        $pinjamanAktif = $this->pinjamanService->cekPinjamanAktif($user_id);
        
        $fields = ['*'];
        $skema_pinjaman = $this->skemaPinjamanService->getAll($fields);

        if ($pinjamanAktif) {
            return view(
                'pinjaman::pengajuanPinjaman.indexAnggota',
                [
                    'skema_pinjaman' => $skema_pinjaman,
                    'disablePengajuan' => $pinjamanAktif,
                    'error' => $pinjamanAktif
                        ? 'Anda masih memiliki pinjaman yang belum selesai'
                        : null
                ]
            );
        }
        $skema = SkemaPinjaman::findOrFail($id_skema);

        return view('pinjaman::pengajuanPinjaman.create', compact('skema'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(PengajuanPinjamanRequest $request)
    {
        $pengajuanPinjaman = $this->pengajuanPinjamanService->create($request->validated());
        return redirect()->route('pengajuanPinjaman.indexAnggota')->with('success', 'Pengajuan pinjaman berhasil diajukan');
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
            $pengajuanPinjaman = $this->pengajuanPinjamanService->getById($fields, $id);
            return response()->json([
                'success' => true,
                'data' => $pengajuanPinjaman
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => true,
                'message' => 'Data pengajuan pinjaman tidak ditemukan',
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $fields = ['*'];
        $pengajuan_pinjaman = $this->pengajuanPinjamanService->getById($fields, $id);
        return view('pinjaman::pengajuanPinjaman.edit', compact('pengajuan_pinjaman'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(PengajuanPinjamanRequest $request, $id)
    {
        try {
            $pengajuanPinjaman = $this->pengajuanPinjamanService->update($request->validated(), $id);
            return redirect()->route('pengajuanPinjaman.indexAnggota')->with('success', 'Data pengajuan pinjaman berhasil diubah');
        } catch(ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Data pengajuan pinjaman tidak ditemukan');
        }
    }

    public function teruskan($id)
    {
        try {
            $pengajuanPinjaman = $this->pengajuanPinjamanService->teruskan($id);
            return redirect()->route('pengajuanPinjaman.indexAnggota')->with('success', 'Status pengajuan pinjaman berhasil diubah');
        } catch(ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Status pengajuan pinjaman gagal diubah');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    // public function destroy($id)
    // {
    //     try {
    //         $this->pengajuanPinjamanService->delete($id);
    //         return response()->json([
    //             'message' => 'Pengajuan pinjaman berhasil dihapus',
    //         ]);
    //     } catch(ModelNotFoundException) {
    //         return response()->json([
    //             'message' => 'Pengajuan pinjaman berhasil dihapus',
    //         ]);
    //     }
    // }
}
