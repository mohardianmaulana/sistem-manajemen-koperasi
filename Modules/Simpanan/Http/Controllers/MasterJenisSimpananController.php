<?php

namespace Modules\Simpanan\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Modules\Simpanan\Http\Requests\MasterJenisSimpananRequest;
use Illuminate\Routing\Controller;
use Modules\Simpanan\Services\MasterJenisSimpananService;
use Modules\Simpanan\Services\SimpananSukarelaService;

class MasterJenisSimpananController extends Controller
{

    protected $service;
    protected $simpananSukarelaService;

    public function __construct(
        MasterJenisSimpananService $service,
        SimpananSukarelaService $simpananSukarelaService
    ) {
        $this->service = $service;
        $this->simpananSukarelaService = $simpananSukarelaService;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $data = $this->service->getAll();

        return view('simpanan::jadwalsimpanan.indexjadwal', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('simpanan::jadwalsimpanan.createjadwal');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(MasterJenisSimpananRequest $request)
    {
         $jadwal = $this->service->store($request->validated());

    if (
        $jadwal->jenis == 'Simpanan Sukarela' &&
        $this->service->isActive($jadwal)
    ) {
        $this->simpananSukarelaService->generatePeriode($jadwal);
    }

    return redirect()
        ->route('master-jenis-simpanan.index')
        ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
         $jenis = $this->service->findById($id);

         return view('simpanan::jadwalsimpanan.editjadwal', compact('jenis'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function update(MasterJenisSimpananRequest $request, $id)
    {
            $jadwal = $this->service->update(
            $id,
            $request->validated()
        );

        
        if (
            $jadwal->nama_jenis_simpanan == 'Simpanan Sukarela' &&
            $this->service->isActive($jadwal)
        ) {
            $this->simpananSukarelaService->generatePeriode($jadwal);
        }

        return redirect()
            ->route('master-jenis-simpanan.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }
}
