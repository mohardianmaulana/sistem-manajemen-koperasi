<?php
namespace Modules\Simpanan\Services;

use Illuminate\Support\Facades\Auth;
use Modules\Simpanan\Repositories\SimpananWajibRepository;

class SimpananWajibService
{
     protected $repository;

    public function __construct(SimpananWajibRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Menampilkan seluruh data.
     */
    public function getAll()
    {
        if (Auth::user()->hasRole('admin')) {
        return $this->repository->getAll();
        }

        return $this->repository->getAll(Auth::id());
    }

    /**
     * Menyimpan pengajuan simpanan wajib.
     */
    public function store(array $data)
    {
        /**
         * Status awal.
         */
        $data['status'] = 'pending';

        /**
         * Tahun otomatis.
         */
        $data['tahun'] = date('Y');

        /**
         * User login.
         */
        $data['id_anggota'] = Auth::id();

        return $this->repository->store($data);
    }

    /**
     * Menampilkan detail.
     */
    public function findById($id)
    {
        return $this->repository->findById($id);
    }

    /**
     * Update data.
     */
    public function update($id, array $data)
    {
        $master = $this->repository->findById($id);

        /**
         * Upload bukti.
         */
        if (isset($data['bukti']) && $data['bukti']) {

            $data['bukti'] = $data['bukti']->store('bukti-simpanan','public');

        }

        /**
         * Update status dan bukti.
         */
        $this->repository->update($master, [

            'status' => $data['status'],

            'bukti' => $data['bukti'] ?? $master->bukti,

        ]);

        /**
         * Jika disetujui maka masuk tabel final.
         */
        if ($master->status == 'selesai') {

            $this->repository->storeSimpanan([

                'nilai'      => $master->nilai,

                'periode'    => $master->periode,

                'tahun'      => $master->tahun,

                'id_anggota' => $master->id_anggota,

            ]);
        }

        return $master;
    }  
}
