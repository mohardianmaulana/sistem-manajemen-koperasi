<?php

namespace Modules\Pinjaman\Http\Controllers\web;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Pinjaman\Http\Requests\SkemaPinjamanRequest;
use Modules\Pinjaman\Services\SkemaPinjamanService;

class SkemaPinjamanController extends Controller
{
    private SkemaPinjamanService $skemaPinjamanServices;

    public function __construct(SkemaPinjamanService $skemaPinjamanServices)
    {
        $this->skemaPinjamanServices = $skemaPinjamanServices;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $fields = ['id', 'nama', 'min_nominal', 'max_nominal', 'min_tenor', 'max_tenor', 'bunga', 'jaminan', 'deskripsi', 'status'];
        $skemaPinjaman = $this->skemaPinjamanServices->getAll($fields);
        return view('pinjaman::skemaPinjaman.index', compact('skemaPinjaman'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('pinjaman::skemaPinjaman.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(SkemaPinjamanRequest $request)
    {
        $skemaPinjaman = $this->skemaPinjamanServices->create($request->validated());
        return redirect()->route('skemaPinjaman.index')->with('success', 'Skema Pinjaman baru berhasil dibuat');
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
            $skemaPinjaman = $this->skemaPinjamanServices->getById($fields, $id);
            return response()->json([
                'success' => 'true',
                'data' => $skemaPinjaman,
            ], 200);
        } catch (ModelNotFoundException $e) { // jika terjadi ModelNotFoundException, maka detail errornya disimpan ke variabel $e
            return response()->json([
                'success' => 'false',
                'message' => 'Data skema tidak ditemukan',
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $fields = ['*'];
        $skemaPinjaman = $this->skemaPinjamanServices->getById($fields, $id);
        return view('pinjaman::skemaPinjaman.edit', compact('skemaPinjaman'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(SkemaPinjamanRequest $request, $id)
    {
        try {
            $skemaPinjaman = $this->skemaPinjamanServices->update($request->validated(), $id);
            return redirect()->route('skemaPinjaman.index')->with('success', 'Data skema pinjaman berhasil diubah');            
        } catch (ModelNotFoundException $e) { // jika terjadi ModelNotFoundException, maka detail errornya disimpan ke variabel $e
            return redirect()->back()->with('error', 'Data skema pinjaman tidak ditemukan');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        try {
            $this->skemaPinjamanServices->delete($id);
            return redirect()->route('skemaPinjaman.index')->with('success', 'Data skema pinjaman berhasil dihapus');
        } catch (ModelNotFoundException $e) { // jika terjadi ModelNotFoundException, maka detail errornya disimpan ke variabel $e
            return redirect()->back()->with('error', 'Data skema pinjaman tidak ditemukan');
        }
    }
}
