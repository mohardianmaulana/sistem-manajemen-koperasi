<?php

namespace Modules\Pinjaman\Http\Controllers\web;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Pinjaman\Services\PinjamanService;
use Modules\Pinjaman\Services\SkemaPinjamanService;

class PinjamanController extends Controller
{
    private PinjamanService $pinjamanService;
    private SkemaPinjamanService $skemaPinjamanService;

    public function __construct(PinjamanService $pinjamanService, SkemaPinjamanService $skemaPinjamanService)
    {
        $this->pinjamanService = $pinjamanService;
        $this->skemaPinjamanService = $skemaPinjamanService;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $fields = ['*'];
        $pinjaman = $this->pinjamanService->getAll($fields, $request->status_pinjaman, $request->id_skema_pinjaman);
        $skemaPinjaman = $this->skemaPinjamanService->getAll($fields);
        return view('pinjaman::pinjaman.index', compact('pinjaman', 'skemaPinjaman'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    // public function create()
    // {
    //     return view('pinjaman::create');
    // }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    // public function store(Request $request)
    // {
    //     //
    // }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        try {
            $fields = ['*'];
            $pinjaman = $this->pinjamanService->getById($fields, $id);
            return response()->json([
                'success' => 'true',
                'data' => $pinjaman,
            ]);            
        } catch (ModelNotFoundException) {
            return response()->json([
                'success' => 'false',
                'message' => 'Data pinjaman tidak ditemukan',
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    // public function edit($id)
    // {
    //     return view('pinjaman::edit');
    // }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    // public function update(Request $request, $id)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    // public function destroy($id)
    // {
    //     //
    // }
}
