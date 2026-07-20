<?php

namespace Modules\Pinjaman\Http\Controllers\web;

use Exception;
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
    public function indexVerifikasi()
    {
        $fields = ['*'];
        $pembayaran = $this->pembayaranService->getAll($fields);
        return view('pinjaman::pembayaran.indexVerifikasi', compact('pembayaran'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function storeManual(PembayaranRequest $request)
    {
        try {
            $pembayaran = $this->pembayaranService->createManual($request);
            return redirect()->route('angsuran.indexAnggota')->with('success', 'Pembayaran manual berhasil dilakukan');
        } catch (Exception $e) {
            return redirect()
                ->route('angsuran.indexAnggota')
                ->with('error', 'Terjadi kesalahan saat memproses data.');
        }
    }

    public function storeUlangManual(PembayaranRequest $request)
    {
        try {
            $pembayaran = $this->pembayaranService->createUlangManual($request);
            return redirect()->route('angsuran.indexAnggota')->with('success', 'Pembayaran manual ulang berhasil dilakukan');
        } catch (Exception $e) {
            return redirect()
                ->route('angsuran.indexAnggota')
                ->with('error', 'Terjadi kesalahan saat memproses data.');
        }
    }

    public function storeAutoDebet(PembayaranRequest $request)
    {
        try {
            $pembayaran = $this->pembayaranService->createAuto($request);
            return redirect()->route('angsuran.index')->with('success', 'Pembayaran auto debet berhasil dilakukan');
        } catch (Exception $e) {
            return redirect()
                ->route('angsuran.index')
                ->with('error', 'Terjadi kesalahan saat memproses data.');
        }
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
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update($id)
    {
        try {
            $pembayaran = $this->pembayaranService->update($id);
            return redirect()->route('pembayaran.indexVerifikasi')->with('success', 'Pembayaran berhasil diverifikasi');           
        } catch (Exception $e) {
            return redirect()
                ->route('pembayaran.indexVerifikasi')
                ->with('error', 'Terjadi kesalahan saat memproses data.');
        }
    }

    public function gagalUpdate(Request $request, $id)
    {
        try {
            $pembayaran = $this->pembayaranService->gagalUpdate(['catatan' => $request->catatan], $id);
            return redirect()->route('pembayaran.indexVerifikasi')->with('success', 'Pembayaran berhasil diverifikasi');           
        } catch (Exception $e) {
            return redirect()
                ->route('pembayaran.indexVerifikasi')
                ->with('error', 'Terjadi kesalahan saat memproses data.');
        }
    }
}
