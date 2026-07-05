<?php

namespace Modules\Simpanan\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Modules\Simpanan\Http\Requests\MasterSimpananSukarelaRequest;
use Modules\Simpanan\Services\SimpananSukarelaService;
use Illuminate\Routing\Controller;

class SimpananSukarelaController extends Controller
{
     protected $service;

    public function __construct(SimpananSukarelaService $service)
    {
        $this->service = $service;
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
        return view('simpanan::simpanansukarela.createSimpananSukarela');
    }

    /**
     * Store a newly created resource in storage.
     * @param SimpananSukarelaRequest $request
     * @return Renderable
     */
    public function store(MasterSimpananSukarelaRequest $request)
    {
        $this->service->store($request->validated());

        return redirect()->route('simpanan-sukarela.index')->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $simpanan= $this->service->findById($id);

        return view('simpanan::simpanansukarela.editSimpananSukarela', compact('simpanan'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function update($id, MasterSimpananSukarelaRequest $request)
    {
         $this->service->update($id, $request->validated());

         return redirect()->route('simpanan-sukarela.index')->with('success', 'Data simpanan berhasil diperbarui');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function edit()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
