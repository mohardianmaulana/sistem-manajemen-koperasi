<?php
    namespace Modules\Simpanan\Services;


    use Illuminate\Support\Facades\Auth;
    use Modules\Simpanan\Entities\SimpananPokok;

    class SimpananPokokService
    {
        public function getAll()
        {
            //$idAnggota = Auth::user()->anggota->id;
            //return SimpananPokok::where('id_anggota', $idAnggota)->get();
            return SimpananPokok::paginate(5);
        }

        public function store(array $data)
        {
            $data['status'] = 'pending';

            // ambil user login
            if (!isset($data['id_anggota'])) {
            throw new \Exception('id_anggota wajib diisi');
        }
            //$data['id_anggota'] = $data['id_anggota'] ?? 1;
            //$data['id_anggota'] = Auth::user()->anggota->id ?? Auth::id();

            // default bukti
            $data['bukti'] = null;

            // kalau ada upload file
            if (isset($data['bukti']) && is_object($data['bukti'])) {
                $data['bukti'] = $data['bukti']->store('bukti-simpanan');
            }

            return SimpananPokok::create($data);
        }

        public function findById($id)
        {
            return SimpananPokok::findOrFail($id);
        }

        public function update($id, array $data)
        {
            $simpanan = SimpananPokok::findOrFail($id);
            
            // =====================
            // HANDLE FILE BUKTI
            // =====================
            if (isset($data['bukti'])) {
                $data['bukti'] = $data['bukti']->store('bukti-simpanan');
            }
        
            // =====================
            // UPDATE DATA
            // =====================
            $simpanan->update([
                'nilai'   => $data['nilai'] ?? $simpanan->nilai,
                'tanggal' => $data['tanggal'] ?? $simpanan->tanggal,
                'status'  => $data['status'] ?? $simpanan->status,
                'bukti'   => $data['bukti'] ?? $simpanan->bukti,
            ]);

            return $simpanan;
        }
 }