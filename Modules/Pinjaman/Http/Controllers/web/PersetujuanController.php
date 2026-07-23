<?php

namespace Modules\Pinjaman\Http\Controllers\web;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Pinjaman\Services\PersetujuanService;

class PersetujuanController extends Controller
{
    private PersetujuanService $persetujuanService;

    public function __construct(PersetujuanService $persetujuanService)
    {
        $this->persetujuanService = $persetujuanService;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $role = $user->roles->first()->name;
        $persetujuan = $this->persetujuanService->getByRole($role);
        return view('pinjaman::persetujuan.index', compact('persetujuan'));
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
            $persetujuan = $this->persetujuanService->getById($fields, $id);
            return response()->json([
                'success' => 'true',
                'data' => $persetujuan,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => 'false',
                'message' => 'Data persetujuan tidak ditemukan',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function setujui($id)
    {
        try {
            $user = Auth::user();
            $role = $user->roles->first()->name;

            $fields = ['*'];
            $persetujuanData = $this->persetujuanService->getById($fields, $id);
            if ($persetujuanData->role !== $role) {
                // Jika data itu milik 'bendahara' tapi yang mengubah 'wadir', langsung blokir
                throw new Exception(
                    'Anda tidak memiliki hak akses untuk menyetujui persetujuan ini.'
                );
            }

            if ($role == 'bendahara') {
                $persetujuan = $this->persetujuanService->updateStatusBendaharaSetuju($id);
            } elseif ($role == 'wadir') {
                $persetujuan = $this->persetujuanService->updateStatusWadirSetuju($id);
            } elseif ($role == 'ketua') {
                $persetujuan = $this->persetujuanService->updateStatusKetuaSetuju($id);
            } else {
                return redirect()->back()->with('error', 'Role tidak memiliki akses untuk persetujuan');
            }

            return redirect()->route('persetujuan.index')->with('success', 'Status pengajuan pinjaman berhasil disetujui');
        } catch (Exception $e) {
            return redirect()
                ->route('persetujuan.index')
                ->with('error', 'Terjadi kesalahan saat memproses data.');
        }
    }

    public function tolak(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $role = $user->roles->first()->name;

            $request->validate([
                'catatan' => 'required|string|max:200'
            ]);

            $fields = ['*'];
            $persetujuanData = $this->persetujuanService->getById($fields, $id);
            if ($persetujuanData->role !== $role) {
                // Jika data itu milik 'bendahara' tapi yang mengubah 'wadir', langsung blokir
                throw new Exception(
                    'Anda tidak memiliki hak akses untuk menolak persetujuan ini.'
                );
            }

            if ($role == 'bendahara') {
                $persetujuan = $this->persetujuanService->updateStatusBendaharaTidakSetuju(['catatan' => $request->catatan], $id);
            } elseif ($role == 'wadir') {
                $persetujuan = $this->persetujuanService->updateStatusWadirTidakSetuju(['catatan' => $request->catatan], $id);
            } elseif ($role == 'ketua') {
                $persetujuan = $this->persetujuanService->updateStatusKetuaTidakSetuju(['catatan' => $request->catatan], $id);
            } else {
                return redirect()->back()->with('error', 'Role tidak memiliki akses untuk persetujuan');
            }
    
            return redirect()->route('persetujuan.index')->with('success', 'Status pengajuan pinjaman berhasil ditolak');
        } catch (Exception $e) {
            return redirect()
                ->route('persetujuan.index')
                ->with('error', 'Terjadi kesalahan saat memproses data.');
        }
    }

    public function persetujuanAkhir(Request $request, $id)
    {
        $request->validate([
            'dokumen_ttd' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
        try {
            $this->persetujuanService->persetujuanAkhir($request->file('dokumen_ttd'), $id);
            return redirect()->route('pengajuanPinjaman.index')
                    ->with('success', 'Data pengajuan berhasil diteruskan ke bendahara untuk dicairkan');
        } catch (Exception $e) {
            return redirect()
                ->route('pengajuanPinjaman.index')
                ->with('error', 'Terjadi kesalahan saat memproses data.');
        }
    }

    public function indexPencairan()
    {
        $fields = ['*'];
        $persetujuan = $this->persetujuanService->getPencairan($fields);
        return view('pinjaman::persetujuan.pencairan', compact('persetujuan'));
    }

    public function pencairan($id)
    {
        try {
            $fields = ['*'];
            $pinjaman = $this->persetujuanService->pencairan($fields, $id);

            return redirect()->route('persetujuan.indexPersetujuan')->with('success', 'Data pinjaman telah aktif');
        } catch (Exception $e) {
            return redirect()
                ->route('persetujuan.index')
                ->with('error', 'Terjadi kesalahan saat memproses data.');
        }
    }
}
