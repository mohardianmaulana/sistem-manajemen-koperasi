<?php

namespace Modules\User\Repositories;

use App\Models\Core\Staff;
use App\Models\Core\Unit;
use App\Models\Core\User;
use Spatie\Permission\Models\Role;

class UserRepository
{
    public function getAll()
    {
        return User::with(['getUnit', 'getStaff', 'roles'])
            ->latest()
            ->paginate(10);
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
        $query = User::query();

        return [
            'totalUser' => $query->count(),

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