<?php

namespace Modules\Simpanan\Services;

use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Simpanan\Repositories\SimpananSukarelaRepository;
use Modules\Simpanan\Services\MasterJenisSimpananService as ServicesMasterJenisSimpananService;

class SimpananSukarelaService
{
    protected $repository;
    protected $masterJenisSimpananService;

    public function __construct(
        SimpananSukarelaRepository $repository,
        ServicesMasterJenisSimpananService $masterJenisSimpananService
    ) {
        $this->repository = $repository;
        $this->masterJenisSimpananService = $masterJenisSimpananService;
    }

    /**
     * LIST DATA
     */
    public function getAll()
    {
        $idAnggota = Auth::user()->hasRole('admin')
            ? null
            : Auth::id();

        return $this->repository->getAll(
            $idAnggota,
            request('bulan'),
            request('tahun')
        );
    }

    public function getSummary()
    {
        $idAnggota = Auth::user()->hasRole('admin')
            ? null
            : Auth::id();

        return $this->repository->getSummary(
            $idAnggota,
            request('bulan'),
            request('tahun')
        );
    }
    /**
     * CREATE
     */
    public function store(array $data)
    {
        $this->masterJenisSimpananService
         ->cekJadwalAktif('Simpanan Sukarela');

        $data['status'] = 'pending';

        $data['tahun'] = date('Y');

        $data['id_anggota'] = Auth::id();

        return $this->repository->store($data);
    }

    /**
     * FIND BY ID
     */
    public function findById($id)
    {
        return $this->repository->findById($id);
    }


   public function updatePengajuan($id, array $data)
    {
        $master = $this->repository->findById($id);

        if (!$master) {
            throw new Exception('Data tidak ditemukan.');
        }

        // Jadwal harus masih aktif
        $this->masterJenisSimpananService
            ->cekJadwalAktif('Simpanan Sukarela');

        // Hanya pending yang boleh diedit
        if ($master->status != 'pending') {
            throw new Exception(
                'Pengajuan hanya dapat diubah ketika status masih Pending.'
            );
        }

        return $this->repository->update($master, [

            'nilai' => $data['nilai'],

            'periode' => $data['periode'],

        ]);
    }

    public function uploadBukti($id, array $data)
    {
        $master = $this->repository->findById($id);

        if (!$master) {
            throw new Exception('Data tidak ditemukan.');
        }

        if ($master->status != 'tidak berhasil') {
            throw new Exception(
                'Bukti transfer hanya dapat diunggah ketika status pengajuan Tidak Berhasil.'
            );
        }

        if (isset($data['bukti']) && $data['bukti']) {

            $data['bukti'] = $data['bukti']
                ->store('bukti-simpanan', 'public');
        }

        return $this->repository->update($master, [

            'bukti' => $data['bukti'],

        ]);
    }

    public function updateStatus($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {

            $master = $this->repository->findById($id);

            if (!$master) {
                throw new Exception('Data simpanan sukarela tidak ditemukan.');
            }

            // Validasi status yang diperbolehkan
            if (!in_array($data['status'], ['selesai', 'tidak berhasil'])) {
                throw new Exception('Status tidak valid.');
            }

            // Update status pengajuan
            $this->repository->update($master, [
                'status' => $data['status'],
            ]);

            /**
             * Jika disetujui (selesai)
             */
            if ($data['status'] == 'selesai') {

                // Hindari data ganda
                if (!$this->repository->sudahMasukSimpanan($master)) {

                    $this->repository->storeSimpanan([

                        'nilai'      => $master->nilai,

                        'periode'    => $master->periode,

                        'tahun'      => $master->tahun,

                        'id_anggota' => $master->id_anggota,

                    ]);
                }
            }

            return $master;
        });
    }

    public function exportAutoDebit()
    {
        return $this->repository->exportAutoDebit(
            request('bulan'),
            request('tahun')
        );
    }

    /**
     * Total autodebit.
     */
    public function totalAutoDebit()
    {
        return $this->repository->totalAutoDebit(
            request('bulan'),
            request('tahun')
        );
    }

    public function generatePeriode($jadwal)
    {
        $masters = $this->repository->getMasterSiapGenerate();

        foreach ($masters as $master) {

            if ($this->repository->sudahAdaPeriode(
                $master->id_anggota,
                $jadwal->tanggal_mulai
            )) {
                continue;
            }

            $this->repository->createPeriode([
                'id_anggota' => $master->id_anggota,
                'nilai'      => $master->nilai,
                'periode'    => $jadwal->tanggal_mulai,
                'tahun'      => Carbon::parse($jadwal->tanggal_mulai)->year,
            ]);
        }
    }
}