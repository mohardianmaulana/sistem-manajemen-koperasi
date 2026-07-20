<?php

namespace Modules\Pinjaman\Http\Controllers\web;

use App\Models\Core\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Pinjaman\Entities\PengajuanPinjaman;
use Modules\Pinjaman\Entities\SkemaPinjaman;
use Modules\Pinjaman\Http\Requests\StorePengajuanPinjamanRequest;
use Modules\Pinjaman\Http\Requests\UpdatePengajuanPinjamanRequest;
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

        $fields = ['*'];
        $skema_pinjaman = $this->skemaPinjamanService->getAllAktif($fields);

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
    public function store(StorePengajuanPinjamanRequest $request)
    {
        try {
            $user_id = Auth::id();
            if ($this->pinjamanService->cekPinjamanAktif($user_id)) {
                return redirect()
                    ->route('pengajuanPinjaman.indexAnggota')
                    ->withErrors([
                        'pinjaman' => 'Anda masih memiliki pinjaman yang belum selesai.'
                    ]);
            }
            $this->pengajuanPinjamanService->create($request->validated());

            return redirect()->route('pengajuanPinjaman.indexAnggota')->with('success', 'Pengajuan pinjaman berhasil diajukan');
        } catch (Exception $e) {
            return redirect()
                ->route('pengajuanPinjaman.indexAnggota')
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
        $pengajuan = $this->pengajuanPinjamanService->getById($fields, $id);
        $skema_pinjaman = $this->skemaPinjamanService->getAll($fields);
        return view('pinjaman::pengajuanPinjaman.edit', compact('pengajuan', 'skema_pinjaman'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(UpdatePengajuanPinjamanRequest $request, $id)
    {
        try {
            $pengajuanPinjaman = $this->pengajuanPinjamanService->update($request->validated(), $id);
            return redirect()->route('pinjaman.indexAnggota')->with('success', 'Data pengajuan pinjaman berhasil diubah');
        } catch (Exception $e) {
            return redirect()
                ->route('pinjaman.indexAnggota')
                ->with('error', 'Terjadi kesalahan saat memproses data.');
        }
    }

    public function revisiJaminan($id)
    {
        $pengajuan = $this->pengajuanPinjamanService->getDetail($id);

        return view('pinjaman::pengajuanPinjaman.editJaminan', compact('pengajuan'));
    }

    public function simpanRevisi(Request $request, $id)
    {
        try {
            $this->pengajuanPinjamanService->simpanRevisi(
                $id,
                $request->all()
            );

            return redirect()->route('pinjaman.indexAnggota')->with('success', 'Dokumen jaminan berhasil direvisi.');
        } catch (Exception $e) {
            return redirect()
                ->route('pinjaman.indexAnggota')
                ->with('error', 'Terjadi kesalahan saat memproses data.');
        }
    }

    public function updateStatusVerifikasi($id)
    {
        try {
            $pengajuan = $this->pengajuanPinjamanService->updateStatusVerifikasi($id);
    
            return redirect()->route('pengajuanPinjaman.index')->with('success', 'Status pengajuan berhasil diubah menjadi verifikasi');
        } catch (Exception $e) {
            return redirect()
                ->route('pengajuanPinjaman.index')
                ->with('error', 'Terjadi kesalahan saat memproses data.');
        }
    }

    public function teruskan($id)
    {
        try {
            $pengajuanPinjaman = $this->pengajuanPinjamanService->teruskan($id);
            return redirect()->route('pengajuanPinjaman.index')->with('success', 'Status pengajuan pinjaman berhasil diubah');
        } catch (Exception $e) {
            return redirect()
                ->route('pengajuanPinjaman.index')
                ->with('error', 'Terjadi kesalahan saat memproses data.');
        }
    }

    public function cetak($id)
    {
        $pengajuan = PengajuanPinjaman::with([
            'users',
            'persetujuan'
        ])->findOrFail($id);

        // Ambil data persetujuan berdasarkan role
        $persetujuan = $pengajuan->persetujuan->keyBy('role');

        $pengurus = [
            'koordinator' => User::where('role_aktif', 'koordinator')->first(),
            'bendahara' => User::where('role_aktif', 'bendahara')->first(),
            'wadir'     => User::where('role_aktif', 'wadir')->first(),
            'ketua'     => User::where('role_aktif', 'ketua')->first(),
        ];

        $pdf = Pdf::loadView(
            'pinjaman::pdf.pengajuan',
            compact('pengajuan', 'persetujuan', 'pengurus')
        );

        return $pdf->stream('Pengajuan-Pinjaman.pdf');
    }

    public function verifikasi(Request $request, $id)
    {
        try {
            $verifikasi = $this->pengajuanPinjamanService->verifikasi(
                $id,
                $request->id_jaminan
            );
            return redirect()->route('pengajuanPinjaman.index')->with('success', 'File jaminan berhasil diverifikasi');  
        } catch (Exception $e) {
            return redirect()
                ->route('pengajuanPinjaman.index')
                ->with('error', 'Terjadi kesalahan saat memproses data.');
        }
    }

    public function tolak(Request $request, $id)
    {
        try {
            $tolak = $this->pengajuanPinjamanService->tolakVerifikasi(
                $id,
                $request->id_jaminan,
                $request->keterangan
            );
            return redirect()->route('pengajuanPinjaman.index')->with('success', 'File jaminan berhasil ditolak');  
        } catch (Exception $e) {
            return redirect()
                ->route('pengajuanPinjaman.index')
                ->with('error', 'Terjadi kesalahan saat memproses data.');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->pengajuanPinjamanService->delete($id);
            return redirect()->route('pinjaman.indexAnggota')->with('success', 'Pengajuan berhasil dibatalkan');
        } catch (Exception $e) {
            return redirect()
                ->route('pinjaman.indexAnggota')
                ->with('error', 'Terjadi kesalahan saat memproses data.');
        }
    }
}
