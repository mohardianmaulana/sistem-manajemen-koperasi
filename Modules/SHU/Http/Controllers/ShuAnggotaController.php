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

    public function create()
    {
        return view('shu::shuanggota.create');
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

            $request->periode_awal,

            $request->periode_akhir,

            $request->persen_jasa_pengurus,

            $request->persen_pajak

        );

            return redirect()->route('shu.index')
                ->with('success','Perhitungan SHU anggota berhasil dilakukan.' );

        } catch (\Exception $e) {

            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);

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
}
