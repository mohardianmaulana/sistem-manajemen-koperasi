<?php
    namespace Modules\Simpanan\Services;

    use Carbon\Carbon;
    use Modules\Simpanan\Entities\MasterJenisSimpanan;

    class MasterJenisSimpananService
    {
         public function getAll()
        {
            return MasterJenisSimpanan::all();
        }

        public function store(array $data)
        {
            return MasterJenisSimpanan::create($data);
        }

        public function update($id, array $data)
        {
            $master = MasterJenisSimpanan::findOrFail($id);

            $master->update($data);

            return $master;
        }

        public function findById($id)
        {
            return MasterJenisSimpanan::findOrFail($id);
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
         * cek apakah aktif
         */
        public function isActive($model)
        {
            return $this->getStatus($model) === 'Aktif';
        }
    }