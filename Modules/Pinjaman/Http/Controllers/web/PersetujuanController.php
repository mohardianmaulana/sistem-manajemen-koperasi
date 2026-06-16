<?php

namespace Modules\Pinjaman\Http\Controllers\web;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Pinjaman\Entities\Persetujuan;
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

    public function indexAnggota()
    {
        $id = Auth::id();
        $persetujuan = $this->persetujuanService->getPersetujuanAnggota($id);
        return view('pinjaman::persetujuan.indexAnggota', compact('persetujuan'));
    }

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
    // public function store(Request $request)
    // {
    //     
    // }

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
    public function setujui($id)
    {
        try {
            $user = Auth::user();
            $role = $user->roles->first()->name;

            $persetujuanData = Persetujuan::findOrFail($id);
            if ($persetujuanData->role !== $role) {
                // Jika data itu milik 'bendahara' tapi yang mengubah 'wadir', langsung blokir
                abort(403, 'Anda tidak memiliki hak akses untuk mengubah data persetujuan ini.');
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
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Data persetujuan tidak ditemukan');
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

            $persetujuanData = Persetujuan::findOrFail($id);
            if ($persetujuanData->role !== $role) {
                // Jika data itu milik 'bendahara' tapi yang mengubah 'wadir', langsung blokir
                abort(403, 'Anda tidak memiliki hak akses untuk mengubah data persetujuan ini.');
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
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Data persetujuan tidak ditemukan');
        }
    }


    public function pencairan($id)
    {
        try {
            $fields = ['*'];
            $pinjaman = $this->persetujuanService->pencairan($fields, $id);

            return redirect()->back()->with('success', 'Data pinjaman aktif');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Data pinjaman gagal aktif');
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
