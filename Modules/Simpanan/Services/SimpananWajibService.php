<?php
namespace Modules\Simpanan\Services;

use Modules\Simpanan\Entities\SimpananWajib;
use Illuminate\Support\Facades\Auth;
use Modules\Simpanan\Entities\MasterSimpananWajib;

class SimpananWajibService
{
    public function getAll()
    {
        $idAnggota = Auth::user()->id;
        return MasterSimpananWajib::with('user')->where('id_anggota', $idAnggota)->paginate(5);
    }

    public function store($data)
    {
        $data['status'] = 'pending';

            // auto tahun
            $data['tahun'] = date('Y');

            // sementara tanpa login
            $data['id_anggota'] = Auth::id();


            return MasterSimpananWajib::create($data);
         
    }

    public function findById($id)
    {
        return MasterSimpananWajib::findOrFail($id);
    }

    public function update($id, $data)
    {
          $master = MasterSimpananWajib::findOrFail($id);

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
                SimpananWajib::create([
                    'nilai'      => $master->nilai,
                    'periode'    => $master->periode,
                    'tahun'      => $master->tahun,
                    'id_anggota' => $master->id_anggota,
                ]);
            }

            return $master;
        }
    
}
