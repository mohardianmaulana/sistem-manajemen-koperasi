<?php

namespace Modules\Simpanan\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Simpanan\Http\Requests\SimpananPokokRequest;
use Modules\Simpanan\Services\SimpananPokokService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class SimpananController extends Controller
{
    protected $simpananPokokService;
    public function __construct(SimpananPokokService $simpananPokokService)
    {
        $this->simpananPokokService = $simpananPokokService;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
         $simpanan = $this->simpananPokokService->getAll();

         return view('simpanan::simpananpokok.indexSimpananPokok', compact('simpanan'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */

    public function create()
    {
        if (!Auth::user()->hasRole('admin')) {
        return redirect()
            ->route('simpanan-pokok.index')
            ->with('error', 'Anda tidak memiliki hak akses untuk mengakses halaman ini.');
        }
        $users = $this->simpananPokokService->getAllUser();
        return view('simpanan::simpananpokok.createSimpananPokok',compact('users') );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */

    public function store(SimpananPokokRequest $request)
    {
        $this->simpananPokokService->store($request->validated());

        return redirect()->route('simpanan-pokok.index')->with('success', 'Simpanan Pokok berhasil diajukan.');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
          $simpanan = $this->simpananPokokService->findById($id);
          return view('simpanan::simpananpokok.editSimpananPokok', compact('simpanan'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function update(SimpananPokokRequest $request, $id)
    {
        $this->simpananPokokService->update($id, $request->validated());

        return redirect()->route('simpanan-pokok.index')->with('success', 'Data berhasil diupdate');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */


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
