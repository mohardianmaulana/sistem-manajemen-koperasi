<?php
    namespace Modules\Simpanan\Services;

    use Illuminate\Support\Facades\Auth;
    use Modules\Simpanan\Repositories\SimpananPokokRepository;

    class SimpananPokokService
    {
       protected $repository;

    public function __construct(SimpananPokokRepository $repository) {
        $this->repository = $repository;
    }

   public function getAll()
    {
        $bulan = request('bulan');
        $tahun = request('tahun');

        $idAnggota = Auth::user()->hasRole('admin')
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
        return $this->repository->store($data);
    }
    
    public function findById($id)
    {
        return $this->repository->findById($id);
    }
    public function update($id, array $data)
    {
        $simpanan = $this->repository->findById($id);

        // Jika yang login adalah admin
        if (Auth::user()->hasRole('admin')) {

            $data = [
                'nilai'   => $data['nilai'] ?? $simpanan->nilai,
                'tanggal' => $data['tanggal'] ?? $simpanan->tanggal,
                'status'  => $data['status'] ?? $simpanan->status,
                'bukti'   => $simpanan->bukti, // admin tidak boleh mengubah bukti
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

        $idAnggota = Auth::user()->hasRole('admin')
            ? null
            : Auth::id();

        return $this->repository->getSummary(
            $idAnggota,
            $bulan,
            $tahun
        );
    }
 }