<?php

namespace Modules\Pinjaman\Http\Controllers\web;

use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Pinjaman\Services\AngsuranService;

class AngsuranController extends Controller
{
    private AngsuranService $angsuranService;

    public function __construct(AngsuranService $angsuranService)
    {
        $this->angsuranService = $angsuranService;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $fields = ['*'];
        $angsuran = $this->angsuranService->getTagihanBulanIni($fields);
        return view('pinjaman::angsuran.index', compact('angsuran'));
    }

    public function cetakDataTagihan()
    {
        $fields = ['*'];
        $tagihan = $this->angsuranService->getTagihanBulanIni($fields);

        $pdf = Pdf::loadView(
            'pinjaman::pdf.dataTagihan',
            compact('tagihan')
        );

        return $pdf->stream('Data-tagihan-angsuran.pdf');
    }

    public function getAngsuranByIdAnggota()
    {
        $id = Auth::id();
        $angsuran = $this->angsuranService->getAngsuran($id);

        return view('pinjaman::angsuran.indexAnggota', compact('angsuran'));
    }

    public function indexVerifikasi()
    {
        $fields = ['*'];
        $angsuran = $this->angsuranService->getVerifikasi($fields);
        return view('pinjaman::angsuran.indexVerifikasi', compact('angsuran'));
    }

    public function gagalDebet($id)
    {
        try {
            $pembayaran = $this->angsuranService->updateGagalDebet($id);
            return redirect()->route('angsuran.index')->with('success', 'Status angsuran berhasil diubah');
        } catch (Exception $e) {
            return redirect()
                ->route('angsuran.index')
                ->with('error', 'Terjadi kesalahan saat memproses data.');
        }
    }
}
