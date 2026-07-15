<?php

namespace Modules\Simpanan\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Modules\Simpanan\Http\Requests\MasterSimpananSukarelaRequest;
use Modules\Simpanan\Services\SimpananSukarelaService;
use Illuminate\Routing\Controller;
use Modules\Simpanan\Services\MasterJenisSimpananService;

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

         return view('simpanan::simpanansukarela.indexSimpananSukarela', compact('data'));
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

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function update($id, MasterSimpananSukarelaRequest $request)
    {
        
        try {

        $this->service->update($id, $request->validated());

        return redirect()
            ->route('simpanan-sukarela.index')
            ->with('success', 'Data simpanan berhasil diperbarui');

        } catch (\Exception $e) {

            return redirect()
             ->back()
             ->withInput()
             ->with('error', $e->getMessage());

        }
    }
}
