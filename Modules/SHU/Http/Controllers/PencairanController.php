<?php

namespace Modules\SHU\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\SHU\Http\Requests\RejectPencairanShuRequest;
use Modules\SHU\Http\Requests\StorePencairanShuRequest;
use Modules\SHU\Http\Requests\UploadBuktiPencairanShuRequest;
use Modules\SHU\Http\Requests\UpdatePencairanShuRequest;
use Modules\SHU\Services\PencairanShuService;

class PencairanController extends Controller
{
     protected $service;

    public function __construct(PencairanShuService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request): Renderable
    {
        $status = $request->status;

        $data = $this->service->getAll($status);

        if (Auth::user()->hasRole('anggota')) {

            $summary = $this->service->getDashboardAnggota();

            return view('shu::pencairan.index', compact(
                'data',
                'summary'
            ));
        }

        $dashboard = $this->service->getDashboardAdmin();

        return view('shu::pencairan.index', compact(
            'data',
            'dashboard'
        ));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
   public function create(Request $request)
    {
        $summary = $this->service->getSummaryPengajuan(
            $request->id_shu_anggota
        );

        return view(
            'shu::pencairan.pengajuan',
            compact('summary')
        );
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function show($id): Renderable
    {
        $data = $this->service->findById($id);

        return view('shu::pencairan.show', compact('data'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function store(StorePencairanShuRequest $request)
    {
        try {

            $this->service->store(
                $request->id_shu_anggota,
                $request->nominal_pengajuan
            );

            return redirect()->route('pencairan.index')->with(
            'success',
            'Pengajuan pencairan SHU berhasil dikirim.'
        );

        } catch (\Exception $e) {

            return redirect()->back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function approve($id)
    {
        try {

            $this->service->approve($id);

             return redirect()->route('pencairan.index')->with(
                'success',
                'Pengajuan berhasil disetujui.'
            );

        } catch (\Exception $e) {

            return redirect()->back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
   public function reject(RejectPencairanShuRequest $request, $id)
    {
        try {

            $this->service->reject(
                $id,
                $request->keterangan
            );

             return redirect()->route('pencairan.index')->with(
                'success',
                'Pengajuan berhasil ditolak.'
            );

        } catch (\Exception $e) {

            return redirect()->back()->with(
                'error',
                $e->getMessage()
            );
        }
    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function cairkan(UploadBuktiPencairanShuRequest $request, $id )
    {
        try {
            $this->service->store(
                $request,
                $id
            );
             return redirect()->route('pencairan.index')->with('success','SHU berhasil dicairkan.');
        } catch (\Exception $e) {

            return redirect()->back()->with(
                'error',
                $e->getMessage()
            );

        }
}

     public function destroy($id)
    {
        try {

            $this->service->delete($id);

            return redirect()->route('pencairan.index')->with(
                'success',
                'Data berhasil dihapus.'
            );

        } catch (\Exception $e) {

            return redirect()->back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function edit($id)
    {
        $data = $this->service->findById($id);

        $summary = $this->service->getSummaryPengajuan(
            $data->id_shu_anggota
        );

        return view(
            'shu::pencairan.edit',
            compact(
                'data',
                'summary'
            )
        );
    }

    public function update(UpdatePencairanShuRequest $request,$id)
    {
        try {

            $this->service->updateNominal(
                $id,
                $request->nominal_pengajuan
            );

            return redirect()
                ->route('pencairan.index')
                ->with(
                    'success',
                    'Pengajuan berhasil diperbarui.'
                );

        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );

        }
    }
}