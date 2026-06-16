<?php

namespace Modules\Pinjaman\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Pinjaman\Transformers\PinjamanResource;
use PinjamanService;

class PinjamanApiController extends Controller
{
    private PinjamanService $pinjamanService;

    public function __construct(PinjamanService $pinjamanService)
    {
        $this->pinjamanService = $pinjamanService;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $fields = ['*'];
        $pinjaman = $this->pinjamanService->getAll($fields);
        return response()->json(PinjamanResource::collection($pinjaman));
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
        try {
            $fields = ['*'];
            $pinjaman = $this->pinjamanService->getById($fields, $id);
            return response()->json(new PinjamanResource($pinjaman));            
        } catch (ModelNotFoundException) {
            return response()->json([
                'message' => 'Data pinjaman tidak ditemukan',
            ]);
        }
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
