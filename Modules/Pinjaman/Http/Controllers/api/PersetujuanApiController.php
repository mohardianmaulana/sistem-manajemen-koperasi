<?php

namespace Modules\Pinjaman\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Pinjaman\Http\Requests\PersetujuanRequest;
use Modules\Pinjaman\Transformers\PersetujuanResource;
use PersetujuanService;

class PersetujuanApiController extends Controller
{
    private PersetujuanService $persetujuanService;

    public function __construct(PersetujuanService $persetujuanService)
    {
        $this->persetujuanService = $persetujuanService;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $role = $user->roles->first()->name;
        $persetujuan = $this->persetujuanService->getByRole($role);
        return response()->json(PersetujuanResource::collection($persetujuan));
    }

    public function indexAnggota()
    {
        $id = Auth::id();
        $persetujuan = $this->persetujuanService->getPersetujuanAnggota($id);
        return response()->json(PersetujuanResource::collection($persetujuan));
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
    public function store(Request $request)
    {
        //
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
            $persetujuan = $this->persetujuanService->getById($fields, $id);
            return response()->json(new PersetujuanResource($persetujuan));
        } catch (ModelNotFoundException) {
            return response()->json([
                'message' => 'Data persetujuan tidak ditemukan',
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
    public function setujui(PersetujuanRequest $request, $id)
    {
        try {
            $user = Auth::user();
            $role = $user->roles->first()->name;
            if ($role == 'bendahara') {
                $persetujuan = $this->persetujuanService->updateStatusBendaharaSetuju($request->validated(), $id);
            } elseif ($role == 'wadir') {
                $persetujuan = $this->persetujuanService->updateStatusWadirSetuju($request->validated(), $id);
            } elseif ($role == 'ketua') {
                $persetujuan = $this->persetujuanService->updateStatusKetuaSetuju($request->validated(), $id);
            } else {
                return response()->json([
                    'message' => 'Role tidak memiliki akses'
                ], 403);
            }

            return response()->json(new PersetujuanResource($persetujuan));
        } catch (ModelNotFoundException) {
            return response()->json([
                'message' => 'Data persetujuan gagal disetujui',
            ]);
        }
    }

    public function tolak(PersetujuanRequest $request, $id)
    {
        try {
            $user = Auth::user();
            $role = $user->roles->first()->name;
            if ($role == 'bendahara') {
                $persetujuan = $this->persetujuanService->updateStatusBendaharaTidakSetuju($request->validated(), $id);
            } elseif ($role == 'wadir') {
                $persetujuan = $this->persetujuanService->updateStatusWadirTidakSetuju($request->validated(), $id);
            } elseif ($role == 'ketua') {
                $persetujuan = $this->persetujuanService->updateStatusKetuaTidakSetuju($request->validated(), $id);
            } else {
                return response()->json([
                    'message' => 'Role tidak memiliki akses'
                ], 403);
            }
    
            return response()->json(new PersetujuanResource($persetujuan));
        } catch (ModelNotFoundException) {
            return response()->json([
                'message' => 'Data persetujuan gagal ditolak',
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
