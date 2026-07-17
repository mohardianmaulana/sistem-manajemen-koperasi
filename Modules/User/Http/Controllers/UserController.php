<?php

namespace Modules\User\Http\Controllers;

use App\Models\Core\Unit as CoreUnit;
use App\Models\Core\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\User\Services\UserService;
use Modules\User\Http\Requests\StoreUserRequest;
use Modules\User\Http\Requests\UpdateUserRequest;


class UserController extends Controller
{

    protected $service;
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
     public function index()
    {
        $users = $this->service->getAll();

        return view('user::index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
     public function create()
    {
        $roles = $this->service->getAllRole();
        $units = $this->service->getAllUnit();
        $staffs = $this->service->getAllStaff();

        return view('user::create', compact(
            'roles',
            'units',
            'staffs'
        ));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(StoreUserRequest $request)
    {
        $this->service->store($request);

        return redirect()
            ->route('user.index')
            ->with('success', 'Data user berhasil ditambahkan.');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
     public function edit($id)
    {
         $user = $this->service->findById($id);

         $roles = $this->service->getAllRole();

         $units = $this->service->getAllUnit();

         $staffs = $this->service->getAllStaff();

         return view('user::edit', compact(
            'user',
            'roles',
            'units',
            'staffs'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
   public function update(UpdateUserRequest $request, $id)
    {
        $this->service->update($request, $id);

        return redirect()
            ->route('user.index')
            ->with('success', 'Data user berhasil diperbarui.');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
        public function destroy($id)
        {
            $this->service->delete($id);

            return redirect()
                ->route('user.index')
                ->with('success', 'Data user berhasil dihapus.');
        }

}
