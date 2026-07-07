<?php

namespace Modules\SHU\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\SHU\Services\ShuKoperasiService;
use Modules\SHU\Http\Requests\ShuKoperasiRequest;

class SHUController extends Controller
{
    protected $shuKoperasiService;
    public function __construct(ShuKoperasiService $shuKoperasiService)
    {
        $this->shuKoperasiService = $shuKoperasiService;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $data = $this->shuKoperasiService->getAll();

        return view('shu::shukoperasi.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $tahun = date('Y');

        $data = $this->shuKoperasiService->getDataCreate($tahun);

        return view('shu::shukoperasi.create',
            array_merge(
                ['tahun' => $tahun],
                $data
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(ShuKoperasiRequest $request)
    {
        $this->shuKoperasiService->store(
        $request->validated()
    );

    return redirect()
        ->route('shu-koperasi.index')
        ->with('success', 'Data SHU berhasil ditambahkan.');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $shu = $this->shuKoperasiService->findById($id);

        return view('shu::shukoperasi.edit', compact('shu'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(ShuKoperasiRequest $request, $id)
    {
        $this->shuKoperasiService->update($id,$request->validated());

    return redirect()->route('shu-koperasi.index')->with('success', 'Data SHU Koperasi berhasil diperbarui.');
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
