<?php

namespace Modules\Pinjaman\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Pinjaman\Repositories\AngsuranRepository;
use Modules\Pinjaman\Repositories\PembayaranRepository;

class PembayaranService {
    private PembayaranRepository $pembayaranRepository;
    private AngsuranRepository $angsuranRepository;

    public function __construct(PembayaranRepository $pembayaranRepository, AngsuranRepository $angsuranRepository)
    {
        $this->pembayaranRepository = $pembayaranRepository;
        $this->angsuranRepository = $angsuranRepository;
    }

    public function getAll($fields)
    {
        return $this->pembayaranRepository->getAll($fields);
    }

    public function getById($fields, $id)
    {
        return $this->pembayaranRepository->getById($fields, $id);
    }

    public function createManual($data)
    {
        $data['status_pembayaran'] = 'verifikasi';
    
        $pembayaran = $this->pembayaranRepository->create($data);

        return $pembayaran;
    }

    public function createAuto($data)
    {
        DB::beginTransaction();
        try {
            $data['status_pembayaran'] = 'sukses';
    
            $pembayaran = $this->pembayaranRepository->create($data);
    
            $fields = ['*'];
            $id_angsuran = $pembayaran->id_angsuran;
    
            $angsuran = $this->angsuranRepository->getById(
                $fields, $id_angsuran
            );
    
            $data = [
                'status_bayar' => 'lunas',
            ];
    
            $updateAngsuran = $this->angsuranRepository->update(
                $data, $id_angsuran
            );

            DB::commit();
        
            return $pembayaran;
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function update($data, $id)
    {
        DB::beginTransaction();
        try {
            $pembayaran = $this->pembayaranRepository->update(
                $data, $id
            );
    
            $fields = ['*'];
            $id_angsuran = $pembayaran->id_angsuran;
    
            $angsuran = $this->angsuranRepository->getById(
                $fields, $id_angsuran
            );
    
            $data = [
                'status_bayar' => 'lunas',
            ];
    
            $updateAngsuran = $this->angsuranRepository->update(
                $data, $id_angsuran
            );

            DB::commit();
    
            return $pembayaran;
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function gagalUpdate($data, $id)
    {
        return $this->pembayaranRepository->update(
            $data, $id
        );
    }
}