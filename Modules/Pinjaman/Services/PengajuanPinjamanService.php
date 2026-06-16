<?php

namespace Modules\Pinjaman\Services;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Pinjaman\Entities\PengajuanPinjaman;
use Modules\Pinjaman\Entities\Persetujuan;
use Modules\Pinjaman\Repositories\PengajuanPinjamanRepository;
use Modules\Pinjaman\Repositories\PersetujuanRepository;
use PhpOffice\PhpWord\TemplateProcessor;

class PengajuanPinjamanService {
    private PengajuanPinjamanRepository $pengajuanPinjamanRepository;
    private PersetujuanRepository $persetujuanRepository;

    public function __construct(PengajuanPinjamanRepository $pengajuanPinjamanRepository, PersetujuanRepository $persetujuanRepository)
    {
        $this->pengajuanPinjamanRepository = $pengajuanPinjamanRepository;
        $this->persetujuanRepository = $persetujuanRepository;
    }

    public function getAll($fields)
    {
        return $this->pengajuanPinjamanRepository->getAll($fields);
    }

    public function getById($fields, $id)
    {
        return $this->pengajuanPinjamanRepository->getById($fields, $id);
    }

    public function create($data)
    {
        DB::beginTransaction();

        try {
            // if (isset($data['path_dokumen']) && $data['path_dokumen'] instanceof UploadedFile) { // instanceof digunakan untuk memastikan itu benar file upload.
            //     $data['path_dokumen'] = $this->uploadFile($data['path_dokumen']);
            // }

            // Simpan data pengajuan
            $pengajuanPinjaman = $this->pengajuanPinjamanRepository->create($data);

            // Generate Word
            // $pathDocx = $this->generateWord($pengajuanPinjaman);

            // Simpan path word
            // $updatePengajuanPinjaman = $this->pengajuanPinjamanRepository->update(['path_dokumen_pinjaman' => $pathDocx], $pengajuanPinjaman->id);

            DB::commit();

            return $pengajuanPinjaman;
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function update($data, $id)
    {
        DB::beginTransaction();

        try {
            $fields = ['*'];
            $pengajuanPinjaman = $this->pengajuanPinjamanRepository->getById($fields, $id);
            // if (!empty($pengajuanPinjaman->path_form_pinjaman)) {
            //     $this->deleteFile($pengajuanPinjaman->path_form_pinjaman);
            // }
            // $data['path_dokumen'] = $this->uploadFile($data['path_dokumen']);
            // if (isset($data['path_dokumen']) && $data['path_dokumen'] instanceof UploadedFile) { // instanceof digunakan untuk memastikan itu benar file upload.
            //     if (!empty($pengajuanPinjaman->path_dokumen)) {
            //         $this->deleteFile($pengajuanPinjaman->path_dokumen);
            //     }
            //     $data['path_dokumen'] = $this->uploadFile($data['path_dokumen']);
            // }

            $pengajuan = $this->pengajuanPinjamanRepository->update($data, $id);

            // Generate Word
            // $pathDocx = $this->generateWord($pengajuanPinjaman);

            // Simpan path word
            // $updatePengajuanPinjaman = $this->pengajuanPinjamanRepository->update(['path_dokumen_pinjaman' => $pathDocx], $pengajuanPinjaman->id);

            DB::commit();

            return $pengajuan;
        } catch (Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    // public function delete($id)
    // {
    //     return $this->pengajuanPinjamanRepository->delete($id);
    // }

    public function teruskan($id)
    {
        $fields = ['*'];
        $pengajuan = $this->pengajuanPinjamanRepository->getById($fields, $id);
        if ($pengajuan['status_pengajuan'] !== 'menunggu') {
            throw new Exception('status tidak valid');
        }
        $updatePengajuanPinjaman = $this->pengajuanPinjamanRepository->update([
            'status_pengajuan' => 'persetujuan_awal'
            ], $pengajuan->id);

        $data = [
            'id_pengajuan' => $id,
            'role' => 'bendahara',
            'disetujui_oleh' => null,
            'status' => 'menunggu',
            'tanggal_disetujui' => null,
            'catatan' => null,
        ];

        $pengajuanUpdate = $this->pengajuanPinjamanRepository->getById($fields, $id);

        if ($pengajuanUpdate['status_pengajuan'] == 'persetujuan_awal') {
            $persetujuan = $this->persetujuanRepository->create($data);
        }

        return $updatePengajuanPinjaman;
    }

    private function uploadFile(UploadedFile $path_dokumen): string
    {
        return $path_dokumen->store('jaminan', 'public');
    }

    private function deleteFile($filePath)
    {
        $relativePath = 'jaminan/'. basename($filePath);
        if (Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }
    }

    private function generateWord($pengajuan)
    {
        $template = new TemplateProcessor(
            storage_path('app/templates/pengajuan.docx')
        );

        $template->setValue(
            'nama',
            $pengajuan->anggota->nama
        );

        $template->setValue(
            'tanggal_pengajuan',
            $pengajuan->tanggal_pengajuan
        );

        $template->setValue(
            'jumlah',
            $pengajuan->jumlah_pengajuan
        );

        $template->setValue(
            'lama_angsuran',
            $pengajuan->lama_angsuran
        );

        $template->setValue(
            'no_hp',
            $pengajuan->no_hp
        );

        $template->setValue(
            'no_ktp',
            $pengajuan->no_ktp
        );

        $template->setValue(
            'no_rekening',
            $pengajuan->no_rekening
        );

        $template->setValue(
            'nama_istri_suami',
            $pengajuan->nama_istri_suami
        );

        $template->setValue(
            'alamat',
            $pengajuan->alamat
        );

        $filename = 'pengajuan_' . $pengajuan->id . '.docx';

        $savePath = storage_path('app/public/pengajuan/' . $filename);

        $template->saveAs($savePath);

        return 'pengajuan/' . $filename;
    }
}