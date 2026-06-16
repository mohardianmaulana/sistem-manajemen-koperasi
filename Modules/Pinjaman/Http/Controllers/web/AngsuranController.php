<?php

namespace Modules\Pinjaman\Http\Controllers\web;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Pinjaman\Services\AngsuranService;

class AngsuranController extends Controller
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
        return view('pinjaman::angsuran.index', compact('angsuran'));
    }

    public function getAngsuranByIdAnggota()
    {
        $id = Auth::id();
        $angsuran = $this->angsuranService->getAngsuran($id);
        return view('pinjaman::angsuran.indexAnggota', compact('angsuran'));
    }

    public function indexVerifikasi()
    {
        $fields = ['*'];
        $angsuran = $this->angsuranService->getVerifikasi($fields);
        return view('pinjaman::angsuran.indexVerifikasi', compact('angsuran'));
    }

    public function gagalDebet($id)
    {
        $data = ['status_bayar' => 'gagal_debet'];
        $pembayaran = $this->angsuranService->updateGagalDebet($data, $id);
        return redirect()->route('angsuran.index')->with('success', 'Status angsuran berhasil diubah');
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
    // public function show($id)
    // {
    //     return view('pinjaman::show');
    // }

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
