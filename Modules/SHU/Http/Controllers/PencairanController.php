<?php

namespace Modules\SHU\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\SHU\Http\Requests\UploadBuktiPencairanShuRequest;
use Modules\SHU\Http\Requests\StorePencairanShuRequest;
use Modules\SHU\Services\PencairanShuService;


class PencairanController extends Controller
{
    protected PencairanShuService $service;

    public function __construct(PencairanShuService $service)
    {
        $this->service = $service;
    }

    /**
     * Menampilkan daftar pencairan SHU.
     */
    public function index(Request $request): Renderable
    {
        $status = $request->status;

        $tahun = $request->tahun ?? now()->year;

        $data = $this->service->getAll(
            $status,
            $tahun
        );

        $listTahun = $this->service->getListTahun();

        if (Auth::user()->hasRole('anggota')) {

            $summary = $this->service->getDashboardAnggota();

            return view(
                'shu::pencairan.index',
                compact(
                    'data',
                    'summary',
                    'listTahun',
                    'tahun'
                )
            );
        }

        $dashboard = $this->service->getDashboardAdmin();

        return view(
            'shu::pencairan.index',
            compact(
                'data',
                'dashboard',
                'listTahun',
                'tahun'
            )
        );
    }

    /**
     * Menampilkan detail pencairan SHU.
     */
    public function show($id): Renderable
    {
        $data = $this->service->findById($id);

        return view(
            'shu::pencairan.show',
            compact('data')
        );
    }

    /**
     * Melakukan pencairan SHU.
     */
    public function cairkan(
        UploadBuktiPencairanShuRequest $request,
        $id
    ) {
        try {

            $this->service->cairkan(
                $request,
                $id
            );

            return redirect()
                ->route('pencairan.index')
                ->with(
                    'success',
                    'Pencairan SHU berhasil diproses.'
                );

        } catch (\Exception $e) {

            return back()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    /**
     * Menandai pencairan gagal.
     */
    public function gagal(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'required|string|max:255',
        ]);

        try {

            $this->service->gagal(
                $id,
                $request->keterangan
            );

            return redirect()
                ->route('pencairan.index')
                ->with(
                    'success',
                    'Status pencairan berhasil diubah menjadi gagal.'
                );

        } catch (\Exception $e) {

            return back()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    /**
     * Menghapus data pencairan SHU.
     */
    public function destroy($id)
    {
        try {

            $this->service->delete($id);

            return redirect()
                ->route('pencairan.index')
                ->with(
                    'success',
                    'Data pencairan SHU berhasil dihapus.'
                );

        } catch (\Exception $e) {

            return back()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    public function store(StorePencairanShuRequest $request)
    {
        try {

            $jumlah = $this->service->store(
                $request->tahun
            );

            return redirect()
                ->route('pencairan.index')
                ->with(
                    'success',
                    "Berhasil membuat {$jumlah} data pencairan SHU."
                );

        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    $e->getMessage()
                );

        }
    }
}