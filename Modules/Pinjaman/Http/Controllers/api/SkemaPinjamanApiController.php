<?php

namespace Modules\Pinjaman\Http\Controllers\api;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Pinjaman\Http\Requests\SkemaPinjamanRequest;
use Modules\Pinjaman\Services\SkemaPinjamanService;
use Modules\Pinjaman\Transformers\SkemaPinjamanResource;

class SkemaPinjamanApiController extends Controller
{
    private SkemaPinjamanService $skemaPinjamanService;

    public function __construct(SkemaPinjamanService $skemaPinjamanService)
    {
        $this->skemaPinjamanService = $skemaPinjamanService;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $fields = ['*'];
        $skemaPinjaman = $this->skemaPinjamanService->getAll($fields);
        return response()->json(SkemaPinjamanResource::collection($skemaPinjaman));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(SkemaPinjamanRequest $request)
    {
        $skemaPinjaman = $this->skemaPinjamanService->create($request->validated());
        return response()->json(new SkemaPinjamanResource($skemaPinjaman));
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
            $skemaPinjaman = $this->skemaPinjamanService->getById($fields, $id);
            return response()->json(new SkemaPinjamanResource($skemaPinjaman));
        } catch (ModelNotFoundException) {
            return response()->json([
                'message' => 'Data skema pinjaman tidak ditemukan',
            ], 404);
        }
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
            $skemaPinjaman = $this->skemaPinjamanService->update($request->validated(), $id);
            return response()->json(new SkemaPinjamanResource($skemaPinjaman));
        } catch(ModelNotFoundException) {
            return response()->json([
                'message' => 'Data skema pinjaman tidak ditemukan',
            ], 404);
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
            $this->skemaPinjamanService->delete($id);
            return response()->json([
                'message' => 'Skema pinjaman berhasil dihapus',
            ]);
        } catch(ModelNotFoundException) {
            return response()->json([
                'message' => 'Skema pinjaman berhasil dihapus',
            ]);
        }
    }
}
