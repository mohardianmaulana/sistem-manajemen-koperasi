<?php

namespace Modules\Simpanan\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Simpanan\Http\Requests\MasterSimpananWajibRequest;
use Modules\Simpanan\Services\SimpananWajibService;
use Illuminate\Routing\Controller;
use Barryvdh\DomPDF\Facade\Pdf;

class SimpananWajibController extends Controller
{
    protected $service;

    public function __construct(SimpananWajibService $service)
    {
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->service->autoGeneratePeriode();

        $data = $this->service->getAll();
        $summary = $this->service->getSummary();

        return view(
            'simpanan::simpananwajib.indexSimpananWajib',
            compact('data', 'summary')
        );
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('simpanan::simpananwajib.createSimpananWajib');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(MasterSimpananWajibRequest $request)
    {
         $this->service->store($request->validated());

         return redirect()->route('simpanan-wajib.index')->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $simpanan= $this->service->findById($id);

        return view('simpanan::simpananwajib.editSimpananWajib', compact('simpanan'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function update($id, MasterSimpananWajibRequest $request)
    {
         $this->service->update($id, $request->validated());

         return redirect()->route('simpanan-wajib.index')->with('success', 'Data simpanan berhasil diperbarui');
    }

   public function exportAutoDebit(Request $request)
    {
        $data = $this->service->exportAutoDebit();

        if ($data->isEmpty()) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Tidak terdapat data auto debit sesuai filter yang dipilih.'
                );

        }

        $pdf = Pdf::loadView(
            'simpanan::pdf',
            [
                'data'   => $data,
                'total'  => $this->service->totalAutoDebit(),
                'bulan'  => $request->bulan,
                'tahun'  => $request->tahun,
            ]
        );

        return $pdf->download(
            'Daftar Auto Debit Simpanan Wajib.pdf'
        );
    }

    

}
