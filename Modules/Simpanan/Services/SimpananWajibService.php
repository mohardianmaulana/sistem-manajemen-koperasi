<?php

namespace Modules\Simpanan\Services;

use App\Models\Core\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Pinjaman\Services\TelegramService;
use Modules\Simpanan\Repositories\SimpananWajibRepository;

class SimpananWajibService
{
    protected $repository;
    protected $telegramService;

    public function __construct(SimpananWajibRepository $repository, TelegramService $telegramService)
    {
        $this->repository = $repository;
        $this->telegramService = $telegramService;
    }

    /**
     * Generate otomatis periode bulan berjalan.
     */
    public function autoGeneratePeriode()
    {
        DB::transaction(function () {

            $periode = now()->startOfMonth();

            if ($this->repository->periodeExists(
                $periode->month,
                $periode->year
            )) {
                return;
            }

            $lastPeriode = $this->repository->getLastPeriode();

            if (!$lastPeriode) {
                return;
            }

            foreach ($this->repository->getAllAnggota() as $anggota) {

                $this->repository->store([
                    'nilai'      => $lastPeriode->nilai,
                    'periode'    => $periode,
                    'tahun'      => $periode->year,
                    'status'     => 'pending',
                    'id_anggota' => $anggota->id,
                ]);
            }
        });
    }

    /**
     * Menampilkan seluruh data.
     */
    public function getAll()
    {
        $idAnggota = Auth::user()->hasRole('koordinator')
            ? null
            : Auth::id();

        return $this->repository->getAll(
            $idAnggota,
            request('bulan'),
            request('tahun')
        );
    }

    /**
     * Ringkasan.
     */
    public function getSummary()
    {
        $idAnggota = Auth::user()->hasRole('koordinator')
            ? null
            : Auth::id();

        return $this->repository->getSummary(
            $idAnggota,
            request('bulan'),
            request('tahun')
        );
    }

    public function store(array $data)
    {
        if ($this->repository->periodeExists(
            date('m', strtotime($data['periode'])),
            date('Y', strtotime($data['periode']))
        )) {
            throw new Exception('Periode tersebut sudah tersedia.');
        }

        foreach ($this->repository->getAllAnggota() as $anggota) {

            $this->repository->store([
                'nilai'      => $data['nilai'],
                'periode'    => $data['periode'],
                'tahun'      => date('Y', strtotime($data['periode'])),
                'status'     => 'pending',
                'id_anggota' => $anggota->id,
            ]);

        }
    }
    /**
     * Detail.
     */
    public function findById($id)
    {
        return $this->repository->findById($id);
    }

    /**
     * Update.
     */
    public function update($id, array $data)
    {
        $master = $this->repository->findById($id);

        if (!$master) {
            throw new Exception('Data simpanan sukarela tidak ditemukan.');
        }

        if (isset($data['bukti']) && $data['bukti']) {
            $data['bukti'] = $data['bukti']->store(
                'bukti-simpanan',
                'public'
            );
        }

        // Jika anggota hanya upload bukti
        if (Auth::user()->hasRole('anggota')) {

            if ($master->status != 'tidak berhasil') {
                throw new Exception(
                    'Bukti transfer hanya dapat diunggah ketika status pengajuan Tidak Berhasil.'
                );
            }

            return $this->repository->update($master, [
                'bukti' => $data['bukti'] ?? $master->bukti,
            ]);
        }

        // Update status dan bukti
        $this->repository->update($master, [
            'status' => $data['status'],
            'bukti'  => $data['bukti'] ?? $master->bukti,
        ]);

        // Jika selesai, masukkan ke simpanan
        if (
            $data['status'] == 'selesai' &&
            !$this->repository->existsSimpanan(
                $master->id_anggota,
                $master->periode
            )
        ) {
            $this->repository->storeSimpanan([
                'nilai'      => $master->nilai,
                'periode'    => $master->periode,
                'tahun'      => $master->tahun,
                'id_anggota' => $master->id_anggota,
            ]);
        }

        // Kirim Telegram ke anggota
        $anggota = User::find($master->id_anggota);

        if ($anggota && $anggota->telegram_chat_id) {

            if ($data['status'] == 'selesai') {

                $pesan =
                    "✅ <b>Simpanan Sukarela Berhasil</b>

    Halo {$anggota->name},

    Pengajuan simpanan sukarela Anda telah berhasil diproses.

    💰 Nominal : Rp " .
                    number_format($master->nilai, 0, ',', '.') .
                    "
    📅 Periode : {$master->periode}

    Status : <b>Selesai</b>";

            } elseif ($data['status'] == 'tidak berhasil') {

                $pesan =
                    "❌ <b>Simpanan Sukarela Tidak Berhasil</b>

    Halo {$anggota->name},

    Pengajuan simpanan sukarela Anda tidak berhasil diproses.

    💰 Nominal : Rp " .
                    number_format($master->nilai, 0, ',', '.') .
                    "
    📅 Periode : {$master->periode}

    Silakan melakukan pembayaran secara manual melalui sistem koperasi.";
            }

            if (isset($pesan)) {
                $this->telegramService->sendMessage(
                    $anggota->telegram_chat_id,
                    $pesan
                );
            }
        }

        return $master;
    }

    /**
     * Export autodebit.
     */
    public function exportAutoDebit()
    {
        return $this->repository->exportAutoDebit(
            request('bulan'),
            request('tahun')
        );
    }

    /**
     * Total autodebit.
     */
    public function totalAutoDebit()
    {
        return $this->repository->totalAutoDebit(
            request('bulan'),
            request('tahun')
        );
    }
}