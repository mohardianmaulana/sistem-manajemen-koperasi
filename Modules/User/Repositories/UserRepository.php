<?php

namespace Modules\User\Repositories;

use App\Models\Core\Staff;
use App\Models\Core\Unit;
use App\Models\Core\User;
use Spatie\Permission\Models\Role;

class UserRepository
{
    public function getAll($search = null)
    {
        return User::with(['getUnit', 'getStaff', 'roles'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('nip', 'like', '%' . $search . '%')
                    ->orWhereHas('getUnit', function ($unit) use ($search) {
                        $unit->where('nama', 'like', '%' . $search . '%');
                    });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();
    }

    public function findById($id)
    {
        return User::with(['getUnit', 'getStaff', 'roles'])
            ->findOrFail($id);
    }

    public function store(array $data)
    {
        return User::create($data);
    }

    public function update($id, array $data)
    {
        $user = User::findOrFail($id);

        $user->update($data);

        return $user;
    }

    public function delete($id)
    {
        return User::findOrFail($id)->delete();
    }

    public function getAllUnit()
    {
        return Unit::orderBy('nama')->get();
    }

    public function getAllStaff()
    {
        return Staff::orderBy('nama')->get();
    }

    public function getAllRole()
    {
        return Role::orderBy('name')->get();
    }

    public function findByNip($nip)
    {    
        return User::where('nip', $nip)->exists();
    }

    public function getDashboardSummary()
    {
        return [
            'pendingUser' => User::where('status', 1)
                ->count(),

            'activeUser' => User::where('status', 2)
                ->whereHas('roles', function ($query) {
                    $query->where('name', 'anggota');
                })
                ->count(),

            'totalUser' => User::whereHas('roles', function ($query) {
                $query->where('name', 'anggota');
            })->count(),
        ];
    }

    public function summary()
    {
        return [
            'totalUser' => User::count(),

            'pendingUser' => User::where(function ($query) {
                $query->whereNull('username')
                    ->orWhereNull('email');
            })->count(),

            'activeUser' => User::whereNotNull('username')
                                ->whereNotNull('email')
                                ->count(),
        ];
    }
}