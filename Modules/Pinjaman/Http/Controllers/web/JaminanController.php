<?php

namespace Modules\Pinjaman\Http\Controllers\web;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Pinjaman\Http\Requests\JaminanRequest;
use Modules\Pinjaman\Services\JaminanService;

class JaminanController extends Controller
{
    private JaminanService $jaminanService;

    public function __construct(JaminanService $jaminanService)
    {
        $this->jaminanService = $jaminanService;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $fields = ['*'];
        $jaminan = $this->jaminanService->getAll($fields);
        return view('pinjaman::jaminan.index', compact('jaminan'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('pinjaman::jaminan.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(JaminanRequest $request)
    {
        try {
            $jaminan = $this->jaminanService->create($request->validated());
            return redirect()->route('jaminan.index')->with('success', 'Jaminan baru berhasil dibuat');
        } catch (Exception $e) {
            return redirect()
                ->route('jaminan.index')
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
            $jaminan = $this->jaminanService->getById($fields, $id);
            return response()->json([
                'success' => 'true',
                'data' => $jaminan,
            ], 200);
        } catch (ModelNotFoundException $e) { // jika terjadi ModelNotFoundException, maka detail errornya disimpan ke variabel $e
            return response()->json([
                'success' => 'false',
                'message' => 'Data jaminan tidak ditemukan',
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
        $jaminan = $this->jaminanService->getById($fields, $id);
        return view('pinjaman::jaminan.edit', compact('jaminan'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(JaminanRequest $request, $id)
    {
        try {
            $skemaPinjaman = $this->jaminanService->update($request->validated(), $id);
            return redirect()->route('jaminan.index')->with('success', 'Data jaminan berhasil diubah');            
        } catch (Exception $e) {
            return redirect()
                ->route('jaminan.index')
                ->with('error', 'Terjadi kesalahan saat memproses data.');
        }
    }

    public function nonaktif($id)
    {
        try {
            $this->jaminanService->nonaktif($id);
            return redirect()->route('jaminan.index')
                    ->with('success', 'Data jaminan berhasil dinonaktifkan');
        } catch (Exception $e) {
            return redirect()
                ->route('jaminan.index')
                ->with('error', 'Terjadi kesalahan saat memproses data.');
        }
    }

    public function aktif($id)
    {
        try {
            $this->jaminanService->aktif($id);
            return redirect()->route('jaminan.index')
                    ->with('success', 'Data jaminan berhasil diaktifkan');
        } catch (Exception $e) {
            return redirect()
                ->route('jaminan.index')
                ->with('error', 'Terjadi kesalahan saat memproses data.');
        }
    }
}
