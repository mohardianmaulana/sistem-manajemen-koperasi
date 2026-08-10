<?php

namespace Modules\Simpanan\Services;

use Modules\Pinjaman\Repositories\PengajuanPinjamanRepository;
use Modules\Pinjaman\Repositories\PersetujuanRepository;
use Modules\Pinjaman\Repositories\PinjamanRepository;
use Modules\SHU\Repositories\PencairanShuRepository;
use Modules\Simpanan\Repositories\PencairanSimpananRepository;
use Modules\Simpanan\Repositories\SimpananSukarelaRepository;
use Modules\Simpanan\Repositories\SimpananWajibRepository;

class DashboardService
{
    protected $repository;
    protected $simpananWajibRepository;
    protected $pencairanSimpananRepository;
    protected $pencairanShuRepository;
    protected $pinjamanRepository;
    protected $pengajuanPinjamanRepository;
    protected $persetujuanRepository;

    public function __construct(
        SimpananSukarelaRepository $repository,
        SimpananWajibRepository $simpananWajibRepository,
        PencairanSimpananRepository $pencairanSimpananRepository,
        PencairanShuRepository $pencairanShuRepository,
        PinjamanRepository $pinjamanRepository,
        PengajuanPinjamanRepository $pengajuanPinjamanRepository,
        PersetujuanRepository $persetujuanRepository,
    ) {
        $this->repository = $repository;
        $this->simpananWajibRepository = $simpananWajibRepository;
        $this->pencairanSimpananRepository = $pencairanSimpananRepository;
        $this->pencairanShuRepository = $pencairanShuRepository;
        $this->pinjamanRepository = $pinjamanRepository;
        $this->pengajuanPinjamanRepository = $pengajuanPinjamanRepository;
        $this->persetujuanRepository = $persetujuanRepository;
    }

    public function getSummary($idAnggota)
    {
        return [
            'sukarela' => $this->repository
                ->getSimpananSukarelaSummary($idAnggota),

            'wajib' => $this->simpananWajibRepository
                ->getSimpananWajibSummary($idAnggota),

            'pinjaman' => $this->pinjamanRepository
                ->getPinjamanSummary($idAnggota),
        ];
    }

    public function getKoordinatorSummary()
    {
        return [
            'sukarela' => $this->repository
                ->getSummary(),

            'wajib' => $this->simpananWajibRepository
                ->getSummary(),

            'penarikan' => $this->pencairanSimpananRepository
                ->getPenarikanSummary(),
            
            'pinjaman' => $this->pengajuanPinjamanRepository
                ->getPengajuanSummary(),
        ];
    }

    public function getBendaharaSummary()
    {
        return [
            'penarikan' => $this->pencairanSimpananRepository
                ->getBendaharaSummary(),

            'shu' => $this->pencairanShuRepository
                ->getBendaharaSummary(),

            'pinjaman' => $this->persetujuanRepository
                ->getPinjamanSummary(),
        ];
    }

    public function getKetuaSummary()
    {
        return [
            'sukarela' => $this->repository
                ->getSummary(),

            'wajib' => $this->simpananWajibRepository
                ->getSummary(),

            'penarikan' => $this->pencairanSimpananRepository
                ->getPenarikanSummary(),
            
            'pinjaman' => $this->pengajuanPinjamanRepository
                ->getPengajuanSummary(),
        ];
    }
}