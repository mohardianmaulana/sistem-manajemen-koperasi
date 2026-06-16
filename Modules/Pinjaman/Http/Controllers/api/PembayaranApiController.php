<?php

namespace Modules\Pinjaman\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Pinjaman\Http\Requests\PembayaranRequest;
use Modules\Pinjaman\Transformers\PembayaranResource;
use PembayaranService;

class PembayaranApiController extends Controller
{
    private PembayaranService $pembayaranService;

    public function __construct(PembayaranService $pembayaranService)
    {
        $this->pembayaranService = $pembayaranService;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('pinjaman::index');
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
    public function storeManual(PembayaranRequest $request)
    {
        $pembayaran = $this->pembayaranService->createManual($request->validated());
        return response()->json(new PembayaranResource($pembayaran));
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
            $pembayaran = $this->pembayaranService->getById($fields, $id);
            return response()->json(new PembayaranResource($pembayaran));            
        } catch (ModelNotFoundException) {
            return response()->json([
                'message' => 'Data pembayaran tidak ditemukan',
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
    public function update(PembayaranRequest $request, $id)
    {
        try {
            $pembayaran = $this->pembayaranService->update($request->validated(), $id);
            return response()->json(new PembayaranResource($pembayaran));            
        } catch (ModelNotFoundException) {
            return response()->json([
                'message' => 'Data pembayaran gagal diverifikasi',
            ]);
        }
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
