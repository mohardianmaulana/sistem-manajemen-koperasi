<?php

namespace Modules\Pinjaman\Http\Controllers\web;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Pinjaman\Http\Requests\PembayaranRequest;
use Modules\Pinjaman\Services\PembayaranService;

class PembayaranController extends Controller
{
    private PembayaranService $pembayaranService;

    public function __construct(PembayaranService $pembayaranService)
    {
        $this->pembayaranService = $pembayaranService;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    // public function index()
    // {
    //     return view('pinjaman::index');
    // }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    // public function create()
    // {
    //     return view('pinjaman::create');
    // }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function storeManual(PembayaranRequest $request)
    {
        $pembayaran = $this->pembayaranService->createManual($request->validated());
        return redirect()->route('angsuran.indexAnggota')->with('success', 'Pembayaran berhasil dilakukan');
    }

    public function storeAutoDebet(PembayaranRequest $request)
    {
        $pembayaran = $this->pembayaranService->createAuto($request->validated());
        return redirect()->route('angsuran.indexAnggota')->with('success', 'Pembayaran berhasil dilakukan');
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
            $pembayaran = $this->pembayaranService->getById($fields, $id);
            return response()->json([
                'success' => 'true',
                'data' => $pembayaran,
            ]);            
        } catch (ModelNotFoundException) {
            return response()->json([
                'success' => 'false',
                'message' => 'Data pembayaran tidak ditemukan',
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    // public function edit($id)
    // {
    //     return view('pinjaman::edit');
    // }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update($id)
    {
        try {
            $pembayaran = $this->pembayaranService->update(['status_pembayaran' => 'sukses'], $id);
            return redirect()->route('pembayaran.verifikasiPembayaranIndex')->with('success', 'Pembayaran berhasil diverifikasi');           
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Pembayaran gagal diverifikasi');
        }
    }

    public function gagalUpdate(Request $request, $id)
    {
        try {
            $pembayaran = $this->pembayaranService->gagalUpdate(['status_pembayaran' => 'ditolak', 'catatan' => $request->catatan], $id);
            return redirect()->route('pembayaran.verifikasiPembayaranIndex')->with('success', 'Pembayaran berhasil diverifikasi');           
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Pembayaran gagal diverifikasi');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    // public function destroy($id)
    // {
    //     //
    // }
}
