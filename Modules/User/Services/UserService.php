<?php

namespace Modules\User\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\User\Repositories\UserRepository;

class UserService
{
    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Menampilkan seluruh data user
     */
    public function getAll()
    {
        return $this->repository->getAll();
    }

    /**
     * Menampilkan user berdasarkan id
     */
    public function findById($id)
    {
        return $this->repository->findById($id);
    }

    /**
     * Menyimpan data user
     */
    public function store($request)
    {
        return DB::transaction(function () use ($request) {

            $data = $request->validated();

            // Cek apakah NIP sudah pernah terdaftar
            $user = $this->repository->findByNip($data['nip']);

            if ($user) {

                if ($user->status == 1) {
                    throw ValidationException::withMessages([
                        'nip' => 'Pendaftaran Anda sedang menunggu proses verifikasi.'
                    ]);
                }

                if ($user->status == 2) {
                    throw ValidationException::withMessages([
                        'nip' => 'NIP tersebut sudah terdaftar dan telah memiliki akun.'
                    ]);
                }
            }

            if ($request->hasFile('file_sk')) {
                $data['file_sk'] = $request->file('file_sk')
                    ->store('file-sk', 'public');
            }

            $data['username'] = null;
            $data['email'] = null;
            $data['password'] = null;
            $data['staff'] = null;
            $data['no_rek'] = null;
            $data['role_aktif'] = '0';
            $data['status'] = 1;

            return $this->repository->store($data);
        });
    }
    /**
     * Mengubah data user
     */
   public function update($request, $id)
    {
        return DB::transaction(function () use ($request, $id) {

            $data = $request->validated();

            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            if ($request->hasFile('file_sk')) {
                $data['file_sk'] = $request->file('file_sk')
                    ->store('file-sk', 'public');
            }

            // Role aktif sesuai pilihan admin
            $data['role_aktif'] = $request->role;

            // User otomatis menjadi aktif setelah diverifikasi
            $data['status'] = 2;

            // Hapus karena bukan kolom tabel users
            unset($data['role']);

            $user = $this->repository->update($id, $data);

            // Sinkronkan role Spatie
            $user->syncRoles($request->role);

            return $user;
        });
    }

    /**
     * Menghapus user
     */
    public function delete($id)
    {
        return $this->repository->delete($id);
    }

    public function getAllRole()
    {
        return $this->repository->getAllRole();
    }

    /**
     * Menampilkan seluruh unit
     */
    public function getAllUnit()
    {
        return $this->repository->getAllUnit();
    }

    /**
     * Menampilkan seluruh staff
     */
    public function getAllStaff()
    {
        return $this->repository->getAllStaff();
    }

    public function getDashboardSummary()
    {
        return $this->repository->getDashboardSummary();
    }
}