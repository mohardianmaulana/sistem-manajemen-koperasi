<?php

namespace Modules\Simpanan\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Simpanan\Http\Requests\MasterSimpananWajibRequest;
use Modules\Simpanan\Services\SimpananWajibService;
use Illuminate\Routing\Controller;

class SimpananWajibController extends Controller
{
    protected $service;

    public function __construct(SimpananWajibService $service)
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

         return view('simpanan::simpananwajib.indexSimpananWajib', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('simpanan::simpananwajib.createSimpananWajib');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(MasterSimpananWajibRequest $request)
    {
         $this->service->store($request->validated());

         return redirect()->route('simpanan-wajib.index')->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $simpanan= $this->service->findById($id);

        return view('simpanan::simpananwajib.editSimpananWajib', compact('simpanan'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function update($id, MasterSimpananWajibRequest $request)
    {
         $this->service->update($id, $request->validated());

         return redirect()->route('simpanan-wajib.index')->with('success', 'Data simpanan berhasil diperbarui');
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
