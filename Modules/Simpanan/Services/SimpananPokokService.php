<?php
    namespace Modules\Simpanan\Services;


    use Illuminate\Support\Facades\Auth;
    use Modules\Simpanan\Entities\SimpananPokok;

    class SimpananPokokService
    {
        public function getAll()
        {
            $idAnggota = Auth::user()->id;
            return SimpananPokok::with('user')->where('id_anggota', $idAnggota)->paginate(5);
            
        }

        public function store(array $data)
        {
            /**
             * Status awal simpanan.
             */
            $data['status'] = 'pending';

            /**
             * User yang sedang login.
             * Kolom id_anggota mengarah ke tabel users.
             */
            $data['id_anggota'] = Auth::id();

            /**
             * Upload bukti jika ada.
             */
            if (isset($data['bukti']) && $data['bukti']) {

                $data['bukti'] = $data['bukti']->store('bukti-simpanan');

            } else {

                $data['bukti'] = null;

            }

            /**
             * Simpan data.
             */
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