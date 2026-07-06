<?php

namespace Modules\Simpanan\Services;

use Modules\Simpanan\Entities\SimpananSukarela;
use Illuminate\Support\Facades\Auth;
use Modules\Simpanan\Entities\MasterSimpananSukarela;

class SimpananSukarelaService
{
        // ======================
        // LIST DATA
        // ======================
        public function getAll()
        {
           $idAnggota = Auth::user()->id;
            return MasterSimpananSukarela::with('user')->where('id_anggota', $idAnggota)->paginate(5);
        }

        // ======================
        // CREATE (ANGGOTA)
        // ======================
        public function store(array $data)
        {
            $data['status'] = 'pending';

            // auto tahun
            $data['tahun'] = date('Y');

            // sementara tanpa login
            $data['id_anggota'] = Auth::id();

            return MasterSimpananSukarela::create($data);
        }

        // ======================
        // FIND BY ID
        // ======================
        public function findById($id)
        {
            return MasterSimpananSukarela::findOrFail($id);
        }

        // ======================
        // UPDATE (PENGURUS)
        // ======================
        public function update($id, array $data)
        {
            $master = MasterSimpananSukarela::findOrFail($id);

            // upload bukti jika ada
            if (isset($data['bukti']) && is_object($data['bukti'])) {
                $data['bukti'] = $data['bukti']->store('bukti-simpanan');
            }

            // update hanya status + bukti
            $master->update([
                'status' => $data['status'],
                'bukti'  => $data['bukti'] ?? $master->bukti,
            ]);

            // ======================
            // RULE: jika disetujui
            // ======================
            if ($master->status === 'selesai') {
                SimpananSukarela::create([
                    'nilai'      => $master->nilai,
                    'periode'    => $master->periode,
                    'tahun'      => $master->tahun,
                    'id_anggota' => $master->id_anggota,
                ]);
            }

            return $master;
        }

       
}