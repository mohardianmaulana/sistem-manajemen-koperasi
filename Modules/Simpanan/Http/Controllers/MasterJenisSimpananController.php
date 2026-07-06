<?php

namespace Modules\Simpanan\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Modules\Simpanan\Http\Requests\MasterJenisSimpananRequest;
use Illuminate\Routing\Controller;
use Modules\Simpanan\Services\MasterJenisSimpananService;

class MasterJenisSimpananController extends Controller
{
    protected $service;

    public function __construct(MasterJenisSimpananService $service)
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
         $this->service->store($request->validated());

         return redirect()->route('master-jenis-simpanan.index')->with('success', 'Data berhasil ditambahkan');
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
        $this->service->update($id, $request->validated());

        return redirect()->route('master-jenis-simpanan.index')->with('success', 'Data berhasil diubah');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function edit(Request $request, $id)
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
