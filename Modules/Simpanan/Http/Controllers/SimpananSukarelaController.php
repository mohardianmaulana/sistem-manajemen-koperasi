<?php

namespace Modules\Simpanan\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Modules\Simpanan\Http\Requests\MasterSimpananSukarelaRequest;
use Modules\Simpanan\Services\SimpananSukarelaService;
use Illuminate\Routing\Controller;
use Modules\Simpanan\Services\MasterJenisSimpananService;
use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Simpanan\Http\Requests\UpdatePengajuanRequest;
use Modules\Simpanan\Http\Requests\UpdateStatusRequest;
use Modules\Simpanan\Http\Requests\UploadBuktiRequest;

class SimpananSukarelaController extends Controller
{
     protected $service;
     protected $masterJenisSimpananService;

    public function __construct(
    SimpananSukarelaService $service,
    MasterJenisSimpananService $masterJenisSimpananService
    ) {
        $this->service = $service;
        $this->masterJenisSimpananService = $masterJenisSimpananService;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
         $data = $this->service->getAll();
         $summary = $this->service->getSummary();

         return view('simpanan::simpanansukarela.indexSimpananSukarela', compact('data', 'summary'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
       try {

        $this->masterJenisSimpananService
             ->cekJadwalAktif('Simpanan Sukarela');

        return view('simpanan::simpanansukarela.createSimpananSukarela');

    } catch (\Exception $e) {

        return redirect()
            ->route('simpanan-sukarela.index')
            ->with('error', $e->getMessage());

    }
    }

    /**
     * Store a newly created resource in storage.
     * @param SimpananSukarelaRequest $request
     * @return Renderable
     */
    public function store(MasterSimpananSukarelaRequest $request)
    {
        try {

        $this->service->store($request->validated());

        return redirect()
            ->route('simpanan-sukarela.index')
            ->with('success', 'Data berhasil ditambahkan');

    } catch (\Exception $e) {

        return redirect()
            ->back()
            ->withInput()
            ->with('error', $e->getMessage());

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

            $this->masterJenisSimpananService
                ->cekJadwalAktif('Simpanan Sukarela');

            $simpanan = $this->service->findById($id);

            return view(
                'simpanan::simpanansukarela.editSimpananSukarela',
                compact('simpanan')
            );

        } catch (\Exception $e) {

            return redirect()
                ->route('simpanan-sukarela.index')
                ->with('error', $e->getMessage());
        }
    }

    public function updatePengajuan(UpdatePengajuanRequest $request, $id)
    {
        try {

            $this->service->updatePengajuan(
                $id,
                $request->validated()
            );

            return redirect()
                ->route('simpanan-sukarela.index')
                ->with('success', 'Pengajuan berhasil diperbarui.');

        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Form upload bukti
     */
    public function uploadBuktiForm($id)
    {
        $simpanan = $this->service->findById($id);

        return view(
            'simpanan::simpanansukarela.uploadBukti',
            compact('simpanan')
        );
    }

    /**
     * Upload bukti transfer
     */
    public function uploadBukti(UploadBuktiRequest $request, $id)
    {
        try {

            $this->service->uploadBukti(
                $id,
                $request->validated()
            );

            return redirect()
                ->route('simpanan-sukarela.index')
                ->with('success', 'Bukti pembayaran berhasil diunggah.');

        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Form verifikasi
     */
    public function verifikasi($id)
    {
        $simpanan = $this->service->findById($id);

        return view(
            'simpanan::simpanansukarela.verifikasi',
            compact('simpanan')
        );
    }

    /**
     * Update status pengajuan
     */
    public function updateStatus(UpdateStatusRequest $request, $id)
    {
        try {

            $this->service->updateStatus(
                $id,
                $request->validated()
            );

            return redirect()
                ->route('simpanan-sukarela.index')
                ->with('success', 'Status pengajuan berhasil diperbarui.');

        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function exportAutoDebit()
    {
        $data = $this->service->exportAutoDebit();

        if ($data->isEmpty()) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Belum terdapat data auto debit.'
                );

        }

        $pdf = Pdf::loadView(
            'simpanan::pdf',
            [

                'data' => $data,

                'total' => $this->service->totalAutoDebit(),

            ]
        );

        return $pdf->download(
            'Daftar Auto Debit Simpanan Sukarela.pdf'
        );
    }
}
