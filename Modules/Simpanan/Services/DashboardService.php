<?php

namespace Modules\Simpanan\Services;

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

    public function __construct(
        SimpananSukarelaRepository $repository,
        SimpananWajibRepository $simpananWajibRepository,
        PencairanSimpananRepository $pencairanSimpananRepository,
        PencairanShuRepository $pencairanShuRepository
    ) {
        $this->repository = $repository;
        $this->simpananWajibRepository = $simpananWajibRepository;
        $this->pencairanSimpananRepository = $pencairanSimpananRepository;
        $this->pencairanShuRepository = $pencairanShuRepository;
    }

    public function getSummary($idAnggota)
    {
        return [
            'sukarela' => $this->repository
                ->getSimpananSukarelaSummary($idAnggota),

            'wajib' => $this->simpananWajibRepository
                ->getSimpananWajibSummary($idAnggota),
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
        ];
    }

    public function getBendaharaSummary()
    {
        return [
            'penarikan' => $this->pencairanSimpananRepository
                ->getBendaharaSummary(),

            'shu' => $this->pencairanShuRepository
                ->getBendaharaSummary(),
        ];
    }
}