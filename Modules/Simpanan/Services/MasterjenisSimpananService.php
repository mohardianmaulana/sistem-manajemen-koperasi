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

            return $now->between(
                Carbon::parse($model->tanggal_mulai),
                Carbon::parse($model->tanggal_berakhir)
            ) ? 'Aktif' : 'Tidak Aktif';
        }

        /**
         * cek apakah aktif
         */
        public function isActive($model)
        {
            return $this->getStatus($model) === 'Aktif';
        }
    }