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
   public function index(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');

        $data = $this->shuKoperasiService->getAll();
        $summary = $this->shuKoperasiService->getSummary($tahun);

        return view(
            'shu::shukoperasi.index',
            compact(
                'data',
                'summary',
                'tahun'
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
         return view('shu::shukoperasi.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(ShuKoperasiRequest $request)
{
    try {

        $this->shuKoperasiService->store(
            $request->validated()
        );

        return redirect()
            ->route('shu-koperasi.index')
            ->with('success', 'Data SHU berhasil ditambahkan.');

    } catch (\Exception $e) {

        return redirect()
            ->back()
            ->withInput()
            ->withErrors([
                'persentase' => $e->getMessage(),
            ]);
    }
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
        try {

            $this->shuKoperasiService->update(
                $id,
                $request->validated()
            );

            return redirect()
                ->route('shu-koperasi.index')
                ->with('success', 'Data SHU Koperasi berhasil diperbarui.');

        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'persentase' => $e->getMessage(),
                ]);
        }
    }
}
