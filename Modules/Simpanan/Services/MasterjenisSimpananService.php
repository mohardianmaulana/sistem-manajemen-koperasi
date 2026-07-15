<?php
    namespace Modules\Simpanan\Services;

    use Carbon\Carbon;
    use Exception;
    use Modules\Simpanan\Repositories\MasterJenisSimpananRepository;

    class MasterJenisSimpananService
    {
        protected $repository;
        
        public function __construct(MasterJenisSimpananRepository $repository){
        $this->repository = $repository;
        }

        public function getAll()
        {
            return $this->repository->getAll();
        }

        public function store(array $data)
        {
            return $this->repository->store($data);
        }

        public function update($id, array $data)
        {
            return $this->repository->update($id, $data);
        }

        public function findById($id)
        {
            return $this->repository->findById($id);
        }

        /**
         * Status dihitung saat dibutuhkan
         */
        public function getStatus($model)
        {
            $now = Carbon::now();

            $mulai = Carbon::parse($model->tanggal_mulai)->startOfDay();

            $berakhir = Carbon::parse($model->tanggal_berakhir)->endOfDay();

            return $now->between($mulai, $berakhir)
                ? 'Aktif'
                : 'Tidak Aktif';
        }

        /**
         * Cek apakah aktif
         */
        public function isActive($model)
        {
            return $this->getStatus($model) === 'Aktif';
        }

        public function cekJadwalAktif($jenis)
        {
            $jadwal = $this->repository->findByJenis($jenis);

            if (!$jadwal) {throw new Exception("Jadwal {$jenis} belum ditentukan.");
            }
            if (!$this->isActive($jadwal)) {
                throw new Exception(
                    "Jadwal {$jenis} sedang tidak aktif."
                );
            }
            return $jadwal;
        }
    }