<?php

namespace Modules\Rat\Http\Controllers;

use Exception;
use Illuminate\Routing\Controller;
use Modules\Rat\Http\Requests\RatRequest;
use Modules\Rat\Services\RatService;

class RatController extends Controller
{
    protected $service;

    public function __construct(RatService $service)
    {
        $this->service = $service;
    }

    /**
     * Menampilkan daftar RAT.
     */
    public function index()
    {
        $data = $this->service->getAll();

        return view('rat::index', compact('data'));
    }

    /**
     * Menampilkan form tambah RAT.
     */
    public function create()
    {
        return view('rat::create');
    }

    /**
     * Menyimpan data RAT.
     */
    public function store(RatRequest $request)
    {
        try {

            $this->service->store(
                $request->validated()
            );

            return redirect()
                ->route('rat.index')
                ->with(
                    'success',
                    'Data RAT berhasil ditambahkan.'
                );

        } catch (Exception $e) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    /**
     * Menampilkan form ubah RAT.
     */
    public function edit($id)
    {
        $data = $this->service->getById($id);

        return view(
            'rat::edit',
            compact('data')
        );
    }

    /**
     * Memperbarui data RAT.
     */
    public function update(RatRequest $request, $id)
    {
        try {

            $this->service->update(
                $id,
                $request->validated()
            );

            return redirect()
                ->route('rat.index')
                ->with(
                    'success',
                    'Data RAT berhasil diperbarui.'
                );

        } catch (Exception $e) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }
}