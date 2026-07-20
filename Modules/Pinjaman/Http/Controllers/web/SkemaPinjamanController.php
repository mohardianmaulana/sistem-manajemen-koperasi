<?php

namespace Modules\Pinjaman\Http\Controllers\web;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Pinjaman\Http\Requests\SkemaPinjamanRequest;
use Modules\Pinjaman\Services\JaminanService;
use Modules\Pinjaman\Services\SkemaPinjamanService;

class SkemaPinjamanController extends Controller
{
    private SkemaPinjamanService $skemaPinjamanServices;
    private JaminanService $jaminanService;

    public function __construct(SkemaPinjamanService $skemaPinjamanServices, JaminanService $jaminanService)
    {
        $this->skemaPinjamanServices = $skemaPinjamanServices;
        $this->jaminanService = $jaminanService;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $fields = ['*'];
        $skemaPinjaman = $this->skemaPinjamanServices->getAll($fields);
        return view('pinjaman::skemaPinjaman.index', compact('skemaPinjaman'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $fields = ['*'];
        $jaminan = $this->jaminanService->getAll($fields);
        return view('pinjaman::skemaPinjaman.create', compact('jaminan'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(SkemaPinjamanRequest $request)
    {
        try {
            $skemaPinjaman = $this->skemaPinjamanServices->
                                create($request->validated());
            return redirect()
                    ->route('skemaPinjaman.index')
                    ->with('success', 'Skema Pinjaman baru berhasil dibuat');
        } catch (Exception $e) {
            return redirect()
                ->route('skemaPinjaman.index')
                ->with('error', 'Terjadi kesalahan saat memproses data.');
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
        $skemaPinjaman = $this->skemaPinjamanServices
                            ->getById($fields, $id);
        $jaminan = $this->jaminanService->getAll($fields);
        return view('pinjaman::skemaPinjaman.edit', 
                compact('skemaPinjaman', 'jaminan'));
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
            $skemaPinjaman = $this->skemaPinjamanServices
                                ->update($request->validated(), $id);
            return redirect()->route('skemaPinjaman.index')
                    ->with('success', 'Data skema pinjaman berhasil diubah');            
        } catch (Exception $e) {
            return redirect()
                ->route('skemaPinjaman.index')
                ->with('error', 'Terjadi kesalahan saat memproses data.');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function nonaktif($id)
    {
        try {
            $this->skemaPinjamanServices->nonaktif($id);
            return redirect()->route('skemaPinjaman.index')
                    ->with('success', 'Data skema pinjaman berhasil dinonaktifkan');
        } catch (Exception $e) {
            return redirect()
                ->route('skemaPinjaman.index')
                ->with('error', 'Terjadi kesalahan saat memproses data.');
        }
    }

    public function aktif($id)
    {
        try {
            $this->skemaPinjamanServices->aktif($id);
            return redirect()->route('skemaPinjaman.index')
                    ->with('success', 'Data skema pinjaman berhasil diaktifkan');
        } catch (Exception $e) {
            return redirect()
                ->route('skemaPinjaman.index')
                ->with('error', 'Terjadi kesalahan saat memproses data.');
        }
    }
}
