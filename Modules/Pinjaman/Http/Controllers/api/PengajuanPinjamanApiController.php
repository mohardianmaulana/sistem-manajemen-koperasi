<?php

namespace Modules\Pinjaman\Http\Controllers\api;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Pinjaman\Http\Requests\PengajuanPinjamanRequest;
use Modules\Pinjaman\Services\SkemaPinjamanService;
use Modules\Pinjaman\Transformers\PengajuanPinjamanResource;
use PengajuanPinjamanService;
use PinjamanService;

class PengajuanPinjamanApiController extends Controller
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
        return response()->json(PengajuanPinjamanResource::collection($pengajuanPinjaman));
    }

    public function indexAnggota($id)
    {
        $user_id = Auth::id();
        $pinjamanAktif = $this->pinjamanService->cekPinjamanAktif($user_id, $id);

        if ($pinjamanAktif) {
            return response()->json([
                'message' => 'Anda masih memiliki pinjaman yang belum selesai'
            ]);
        }

        $fields = ['*'];
        $skemaPinjaman = $this->skemaPinjamanService->getAll($fields);
        return response()->json(PengajuanPinjamanResource::collection($skemaPinjaman));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(PengajuanPinjamanRequest $request)
    {
        $pengajuanPinjaman = $this->pengajuanPinjamanService->create($request->validated());
        return response()->json(new PengajuanPinjamanResource($pengajuanPinjaman));
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
            return response()->json(new PengajuanPinjamanResource($pengajuanPinjaman));
        } catch (ModelNotFoundException) {
            return response()->json([
                'message' => 'Data pengajuan pinjaman tidak ditemukan',
            ], 404);
        }
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
            return response()->json(new PengajuanPinjamanResource($pengajuanPinjaman));
        } catch(ModelNotFoundException) {
            return response()->json([
                'message' => 'Data pengajuan pinjaman tidak ditemukan',
            ], 404);
        }
    }

    public function teruskan($id)
    {
        try {
            $pengajuanPinjaman = $this->pengajuanPinjamanService->teruskan($id);
            return response()->json(new PengajuanPinjamanResource($pengajuanPinjaman));
        } catch(ModelNotFoundException) {
            return response()->json([
                'message' => 'Status pengajuan pinjaman tidak berhasil diubah',
            ]);
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
