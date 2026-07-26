<?php

namespace Modules\SHU\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\SHU\Entities\PencairanShu;
use Modules\SHU\Repositories\PencairanShuRepository;
use Modules\SHU\Repositories\ShuAnggotaRepository;
use Exception;

class PencairanShuService
{
    protected $repository;
    protected $shuRepository;

    public function __construct(
        PencairanShuRepository $repository,
        ShuAnggotaRepository $shuRepository
    ) {
        $this->repository = $repository;
        $this->shuRepository = $shuRepository;
    }

    public function getAll($status = null)
    {
        if (Auth::user()->hasRole('anggota')) {
            return $this->repository->getByAnggota(Auth::id());
        }

        return $this->repository->getAll($status);
    }

    public function findById($id)
    {
        return $this->repository->findById($id);
    }

    public function store($idShuAnggota, $nominalPengajuan)
    {
        $shu = $this->shuRepository->findById($idShuAnggota);

        if (!$shu) {
            throw new Exception('Data SHU tidak ditemukan.');
        }

        $totalShu = $shu->shu_anggota;

        $totalDicairkan = $this->repository
            ->totalNominalDicairkan($idShuAnggota);

        $totalDiproses = $this->repository
            ->totalNominalDiproses($idShuAnggota);

        $sisaShu = $totalShu - $totalDicairkan - $totalDiproses;

        if ($nominalPengajuan <= 0) {
            throw new Exception('Nominal pencairan harus lebih dari nol.');
        }

        if ($nominalPengajuan > $sisaShu) {
            throw new Exception(
                'Nominal pencairan melebihi sisa SHU yang tersedia.'
            );
        }

        return $this->repository->store([
            'id_shu_anggota'    => $idShuAnggota,
            'nominal_pengajuan' => $nominalPengajuan,
            'tanggal_pengajuan' => Carbon::today(),
            'status'            => PencairanShu::STATUS_MENUNGGU,
        ]);
    }

    public function approve($id)
    {
        return $this->repository->update($id, [
            'status'              => PencairanShu::STATUS_DISETUJUI,
            'tanggal_persetujuan' => Carbon::today(),
            'disetujui_oleh'      => Auth::id(),
        ]);
    }

    public function reject($id, $keterangan = null)
    {
        return $this->repository->update($id, [
            'status'     => PencairanShu::STATUS_DITOLAK,
            'keterangan' => $keterangan,
        ]);
    }

   public function cairkan(Request $request, $id)
{
    $pencairan = $this->repository->findById($id);

    if (!$pencairan) {
        throw new Exception('Data pencairan SHU tidak ditemukan.');
    }

    if ($pencairan->status != PencairanShu::STATUS_DISETUJUI) {
        throw new Exception(
            'Pengajuan belum disetujui atau sudah dicairkan.'
        );
    }

    $pathBukti = null;

    if ($request->hasFile('bukti')) {

        $pathBukti = $request
            ->file('bukti')
            ->store('bukti-pencairan-shu', 'public');

    }

    return $this->repository->update($id, [

        'bukti'              => $pathBukti,

        'status'             => PencairanShu::STATUS_DICAIRKAN,

        'tanggal_pencairan'  => Carbon::today(),

    ]);
}

    public function delete($id)
    {
        return $this->repository->delete($id);
    }

    public function getDashboardAdmin()
    {
        return [
            'total_pengajuan' => $this->repository->totalPengajuan(),
            'menunggu'        => $this->repository->totalMenunggu(),
            'disetujui'       => $this->repository->totalDisetujui(),
            'dicairkan'       => $this->repository->totalDicairkan(),
            'ditolak'         => $this->repository->totalDitolak(),
        ];
    }

    public function getSummaryPengajuan($idShuAnggota)
    {
        $shu = $this->shuRepository->findById($idShuAnggota);

        if (!$shu) {
            throw new Exception('Data SHU tidak ditemukan.');
        }

        $dicairkan = $this->repository
            ->totalNominalDicairkan($idShuAnggota);

        $diproses = $this->repository
            ->totalNominalDiproses($idShuAnggota);

        $riwayat = $this->repository
            ->getRiwayatByShuAnggota($idShuAnggota);

        return [
            'shu'               => $shu,
            'total_shu'         => $shu->shu_anggota,
            'total_dicairkan'   => $dicairkan,
            'total_diproses'    => $diproses,
            'sisa_shu'          => $shu->shu_anggota - $dicairkan - $diproses,
            'riwayat'           => $riwayat,
        ];
    }

    public function getDashboardAnggota()
    {
        $shu = $this->shuRepository->getSummary(Auth::id());

        if (!$shu) {
            return null;
        }

        return $this->getSummaryPengajuan($shu->id);
    }

    public function updateNominal($id, $nominal)
    {
        $pencairan = $this->repository->findById($id);

        if ($pencairan->status !== PencairanShu::STATUS_MENUNGGU) {
            throw new Exception(
                'Pengajuan yang sudah diproses tidak dapat diubah.'
            );
        }

        return $this->repository->update($id, [
            'nominal_pengajuan' => $nominal,
        ]);
    }
    
    public function uploadBukti($id, $pathBukti)
    {
        $pencairan = $this->repository->findById($id);

        if ($pencairan->status != PencairanShu::STATUS_DISETUJUI) {

            throw new Exception(
                'Pengajuan belum disetujui.'
            );
        }

        return $this->repository->update($id, [

            'bukti' => $pathBukti,

            'status' => PencairanShu::STATUS_DICAIRKAN,

            'tanggal_pencairan' => Carbon::today(),

        ]);
    }
}