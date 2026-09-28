<?php
    namespace Modules\Simpanan\Services;

use App\Models\Core\User;
use Illuminate\Support\Facades\Auth;
use Modules\Pinjaman\Services\TelegramService;
use Modules\Simpanan\Repositories\SimpananPokokRepository;

    class SimpananPokokService
    {
       protected $repository;
       protected $telegramService;

    public function __construct(SimpananPokokRepository $repository, TelegramService $telegramService) {
        $this->repository = $repository;
        $this->telegramService = $telegramService;
    }

   public function getAll()
    {
        $bulan = request('bulan');
        $tahun = request('tahun');

        $idAnggota = Auth::user()->hasRole('koordinator')
            ? null
            : Auth::id();

        return $this->repository->getAll(
            $idAnggota,
            $bulan,
            $tahun
        );
    }

    public function getAllUser()
    {
        return $this->repository->getAllUser();
    }

    public function store(array $data)
    {
        $data['status'] = 'pending';

        $simpanan = $this->repository->store($data);

        $anggota = User::find($simpanan->id_anggota);

        if ($anggota && $anggota->telegram_chat_id) {

            $pesan =
                "📢 <b>Pengajuan Simpanan Pokok</b>

    Halo {$anggota->name},

    Pengajuan pembayaran simpanan pokok Anda telah diterima dan sedang menunggu verifikasi admin.

    💰 Nominal : Rp " .
                number_format($simpanan->nilai, 0, ',', '.') .
                "
    📅 Periode : {$simpanan->periode}

    Status : <b>Pending</b>

    Silakan menunggu proses verifikasi dari admin.";

            $this->telegramService->sendMessage(
                $anggota->telegram_chat_id,
                $pesan
            );
        }

        return $simpanan;
    }
    
    public function findById($id)
    {
        return $this->repository->findById($id);
    }
    public function update($id, array $data)
    {
        $simpanan = $this->repository->findById($id);

        // Jika yang login adalah koordinator
        if (Auth::user()->hasRole('koordinator')) {

            $data = [
                'nilai'   => $data['nilai'] ?? $simpanan->nilai,
                'tanggal' => $data['tanggal'] ?? $simpanan->tanggal,
                'status'  => $data['status'] ?? $simpanan->status,
                'bukti'   => $simpanan->bukti, // koordinator tidak boleh mengubah bukti
            ];

        } else {

            // Anggota hanya boleh mengubah bukti
            if (isset($data['bukti']) && $data['bukti']) {

                $data['bukti'] = $data['bukti']->store('bukti-simpanan', 'public');

            }

            $data = [
                'nilai'   => $simpanan->nilai,
                'tanggal' => $simpanan->tanggal,
                'status'  => $simpanan->status,
                'bukti'   => $data['bukti'] ?? $simpanan->bukti,
            ];
        }

        return $this->repository->update($id, $data);
    }

    public function getSummary()
    {
        $bulan = request('bulan');
        $tahun = request('tahun');

        $idAnggota = Auth::user()->hasRole('koordinator')
            ? null
            : Auth::id();

        return $this->repository->getSummary(
            $idAnggota,
            $bulan,
            $tahun
        );
    }

    
 }