<?php

namespace Modules\Pinjaman\Http\Controllers;

use AngsuranService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Pinjaman\Entities\Angsuran;
use Modules\Pinjaman\Transformers\AngsuranResource;

class AngsuranApiController extends Controller
{
    private AngsuranService $angsuranService;

    public function __construct(AngsuranService $angsuranService)
    {
        $this->angsuranService = $angsuranService;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $fields = ['*'];
        $angsuran = $this->angsuranService->getAll($fields);
        return response()->json(AngsuranResource::collection($angsuran));
    }

    public function getAngsuranByIdAnggota($id)
    {
        $angsuran = $this->angsuranService->getAngsuran($id);
        return response()->json(AngsuranResource::collection($angsuran));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('pinjaman::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('pinjaman::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('pinjaman::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
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
