<?php
namespace Modules\Simpanan\Services;

use App\Models\Core\User;
use Illuminate\Support\Facades\Auth;
use Exception;
use Modules\Simpanan\Repositories\SimpananWajibRepository;
use Modules\Simpanan\Services\MasterJenisSimpananService;

class SimpananWajibService
{
     protected $repository;
     protected $masterJenisSimpananService;

    public function __construct(
    SimpananWajibRepository $repository,
    MasterJenisSimpananService $masterJenisSimpananService
    ) {
        $this->repository = $repository;
        $this->masterJenisSimpananService = $masterJenisSimpananService;
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
        $anggota = $this->repository->getAllAnggota();

        foreach ($anggota as $user) {

            $this->repository->store([
                'nilai'      => $data['nilai'],
                'periode'    => $data['periode'],
                'tahun'      => date('Y'),
                'status'     => 'pending',
                'id_anggota' => $user->id,
            ]);

        }
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
         * Upload bukti
         */
        if (isset($data['bukti']) && $data['bukti']) {

            $data['bukti'] = $data['bukti']->store(
                'bukti-simpanan',
                'public'
            );
        }

        /**
         * Jika anggota
         */
        if (Auth::user()->hasRole('anggota')) {

            if ($master->status != 'tidak berhasil') {
                throw new Exception(
                    'Bukti transfer hanya dapat diunggah ketika status pengajuan Tidak Berhasil.'
                );
            }

            $this->repository->update($master, [

                'bukti' => $data['bukti'] ?? $master->bukti,

            ]);

            return $master;
        }

        /**
         * Jika admin
         */
        $this->repository->update($master, [

            'status' => $data['status'],

            'bukti' => $data['bukti'] ?? $master->bukti,

        ]);

        /**
         * Jika disetujui
         */
        if ($data['status'] == 'selesai') {

    if (!$this->repository->existsSimpanan(
        $master->id_anggota,
        $master->periode
    )) {

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
}