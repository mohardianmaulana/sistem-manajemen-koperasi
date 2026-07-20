<?php

namespace Modules\User\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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

                // Upload SK
                if ($request->hasFile('file_sk')) {

                    $data['file_sk'] = $request->file('file_sk')
                        ->store('file-sk', 'public');

                }

                /**
                 * Data default saat pendaftaran
                 */
                $data['username'] = null;
                $data['email'] = null;
                $data['password'] = null;
                $data['staff'] = null;
                $data['no_rek'] = null;
                $data['role_aktif'] = '0';

                /**
                 * Status Menunggu Verifikasi
                 */
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

            // Password diubah jika diisi
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            // Upload SK baru jika ada
            if ($request->hasFile('file_sk')) {
                $data['file_sk'] = $request->file('file_sk')
                    ->store('file-sk', 'public');
            }

            return $this->repository->update($id, $data);
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
}