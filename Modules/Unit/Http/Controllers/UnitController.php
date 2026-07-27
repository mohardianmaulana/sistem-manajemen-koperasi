<?php

namespace Modules\Unit\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Unit\Services\UnitService;
use Modules\Unit\Http\Requests\StoreUnitRequest;
use Modules\Unit\Http\Requests\UpdateUnitRequest;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
   protected $service;

    public function __construct(UnitService $service)
    {
        $this->service = $service;
    }

    /**
     * Menampilkan daftar unit.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $units = $this->service->getAll($search);

        return view('unit::index', compact('units'));
    }

    /**
     * Menampilkan form tambah unit.
     */
    public function create()
    {
        return view('unit::create');
    }

    /**
     * Menyimpan data unit.
     */
    public function store(StoreUnitRequest $request)
    {
        try {

            $this->service->store($request->validated());

            return redirect()
                ->route('unit.index')
                ->with('success', 'Data unit berhasil ditambahkan.');

        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());

        }
    }

    /**
     * Menampilkan detail unit.
     */
    public function show($id)
    {
        $unit = $this->service->findById($id);

        return view('unit::show', compact('unit'));
    }

    /**
     * Menampilkan form edit unit.
     */
    public function edit($id)
    {
        $unit = $this->service->findById($id);

        return view('unit::edit', compact('unit'));
    }

    /**
     * Memperbarui data unit.
     */
    public function update(UpdateUnitRequest $request, $id)
    {
        try {

            $this->service->update(
                $request->validated(),
                $id
            );

            return redirect()
                ->route('unit.index')
                ->with('success', 'Data unit berhasil diperbarui.');

        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());

        }
    }

    /**
     * Menghapus data unit.
     */
    public function destroy($id)
    {
        try {

            $this->service->destroy($id);

            return redirect()
                ->route('unit.index')
                ->with('success', 'Data unit berhasil dihapus.');

        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->with('error', $e->getMessage());

        }
    }
}
