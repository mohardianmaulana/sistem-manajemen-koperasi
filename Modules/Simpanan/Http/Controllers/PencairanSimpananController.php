<?php

namespace Modules\Simpanan\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Simpanan\Http\Requests\GagalPencairanRequest;
use Modules\Simpanan\Http\Requests\TolakPencairanRequest;
use Modules\Simpanan\Services\PencairanSimpananService;

class PencairanSimpananController extends Controller
{
    protected $service;

        public function __construct(PencairanSimpananService $service)
        {
            $this->service = $service;
        }


         /* Menampilkan daftar pencairan.*/
       public function index()
        {
            $data = $this->service->getAll();

            if (auth()->user()->hasRole('anggota')) {

                $saldo = $this->service->hitungSaldo(auth()->id());

                $totalPending = $this->service->totalPendingAnggota(auth()->id());

                $totalDicairkan = $this->service->totalDicairkan(auth()->id());

                return view('simpanan::pencairan.index', compact('data','saldo','totalPending','totalDicairkan'));
            }

            if (auth()->user()->hasRole('koordinator')) {

                $totalPending = $this->service->totalPending();

                $totalDiverifikasi = $this->service->totalDiverifikasi();

                $totalDitolak = $this->service->totalDitolak();

                return view('simpanan::pencairan.index',compact('data','totalPending','totalDiverifikasi','totalDitolak'));
            }

            if (auth()->user()->hasRole('bendahara')) {

                $totalSiapDicairkan = $this->service->totalSiapDicairkan();

                $totalSudahDicairkan = $this->service->totalSudahDicairkan();

                $totalGagal = $this->service->totalGagal();

                return view('simpanan::pencairan.index',compact('data','totalSiapDicairkan','totalSudahDicairkan','totalGagal'));
            }

        }

        /* Menyimpan pengajuan pencairan.*/
        public function store()
        {
            try {

                $this->service->store();

                return redirect()
                    ->route('pencairan-simpanan.index')
                    ->with(
                        'success',
                        'Pengajuan pencairan berhasil dibuat.'
                    );

            } catch (\Exception $e) {

                return back()->with(
                    'error',
                    $e->getMessage()
                );
            }
        }

        /* Menampilkan detail pencairan.*/
        public function show($id)
        {
            $data = $this->service->getById($id);

            return view('simpanan::pencairan.show', compact('data'));
        }

        /* Verifikasi pengajuan oleh Koordinator.*/
        public function verifikasi($id)
        {
            try {

                $this->service->verifikasi($id);

                return back()->with('success','Pengajuan berhasil diverifikasi.');

            } catch (\Exception $e) {

                return back()->with('error',$e->getMessage());
            }
        }

        /* Menolak pengajuan pencairan.*/
        public function tolak(TolakPencairanRequest $request, $id)
        {
            try {

                $this->service->tolak(
                    $id,
                    $request->validated()['catatan']
                );

                return back()->with('success','Pengajuan berhasil ditolak.');
            } catch (\Exception $e) {
                return back()->with('error', $e->getMessage());
            }
        }

        /* Melakukan pencairan oleh Bendahara.*/
        public function cairkan($id)
        {
            try {

                $this->service->cairkan($id);

                return back()->with(
                    'success',
                    'Pencairan berhasil dilakukan.'
                );

            } catch (\Exception $e) {

                return back()->with(
                    'error',
                    $e->getMessage()
                );
            }
        }


        /* Menandai pencairan gagal.*/
    public function gagal(GagalPencairanRequest $request, $id)
    {
        try {

            $this->service->gagal(
            $id,
            $request->validated()['catatan']
            );

            return back()->with('success', 'Status pencairan berhasil diperbarui.');
            } catch (\Exception $e) {
                return back()->with('error',$e->getMessage()
            );}
        }

}
