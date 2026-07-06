<?php

namespace Modules\SHU\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\SHU\Services\ShuAnggotaService;
use Modules\SHU\Http\Requests\ShuAnggotaRequest;
use Illuminate\Routing\Controller;

class ShuAnggotaController extends Controller
{
    protected $shuAnggotaService;
    public function __construct(ShuAnggotaService $shuAnggotaService)
    {
        $this->shuAnggotaService = $shuAnggotaService;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $data = $this->shuAnggotaService->getAll();

        return view('shu::shuanggota.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(ShuAnggotaRequest $request)
    {
            try {

                $this->shuAnggotaService->hitungSemuaAnggota(
                    $request->tahun
                );

                return redirect()
                    ->back()
                    ->with(
                        'success',
                        'Perhitungan SHU berhasil dilakukan.'
                    );

            } catch (\Exception $e) {

                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        $e->getMessage()
                    );

            }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('shu::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('shu::edit');
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
