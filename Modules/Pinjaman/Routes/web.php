<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Modules\Pinjaman\Http\Controllers\web\AngsuranController;
use Modules\Pinjaman\Http\Controllers\web\JaminanController;
use Modules\Pinjaman\Http\Controllers\web\PengajuanPinjamanController;
use Modules\Pinjaman\Http\Controllers\web\PembayaranController;
use Modules\Pinjaman\Http\Controllers\web\PersetujuanController;
use Modules\Pinjaman\Http\Controllers\web\PinjamanController;
use Modules\Pinjaman\Http\Controllers\web\SimulasiPinjamanController;
use Modules\Pinjaman\Http\Controllers\web\SkemaPinjamanController;

Route::prefix('pinjaman')->group(function() {
    Route::get('/', 'PinjamanController@index');
});

Route::prefix('simulasi_pinjaman')->middleware('web')->group(function () {
    Route::get('/', [SimulasiPinjamanController::class, 'index'])->name('simulasiPinjaman.index');
    Route::get('/{id}', [SimulasiPinjamanController::class, 'hasil'])->name('simulasiPinjaman.hasil');
    Route::post('/hitung', [SimulasiPinjamanController::class, 'hitung'])->name('simulasi-pinjaman.hitung');
});

Route::prefix('skema_pinjaman')->middleware('web')->group(function () {
    Route::get('/', [SkemaPinjamanController::class, 'index'])->name('skemaPinjaman.index');
    Route::get('/create', [SkemaPinjamanController::class, 'create'])->name('skemaPinjaman.create');
    Route::post('/store', [SkemaPinjamanController::class, 'store'])->name('skemaPinjaman.store');
    Route::get('/edit/{id}', [SkemaPinjamanController::class, 'edit'])->name('skemaPinjaman.edit');
    Route::put('/update/{id}', [SkemaPinjamanController::class, 'update'])->name('skemaPinjaman.update');
    Route::patch('/nonaktif/{id}', [SkemaPinjamanController::class, 'nonaktif'])->name('skemaPinjaman.nonaktif');
    Route::patch('/aktif/{id}', [SkemaPinjamanController::class, 'aktif'])->name('skemaPinjaman.aktif');
});

Route::prefix('jaminan')->middleware('web')->group(function () {
    Route::get('/index', [JaminanController::class, 'index'])->name('jaminan.index');
    Route::get('/create', [JaminanController::class, 'create'])->name('jaminan.create');
    Route::post('/store', [JaminanController::class, 'store'])->name('jaminan.store');
    Route::get('/edit/{id}', [JaminanController::class, 'edit'])->name('jaminan.edit');
    Route::put('/update/{id}', [JaminanController::class, 'update'])->name('jaminan.update');
    Route::patch('/nonaktif/{id}', [JaminanController::class, 'nonaktif'])->name('jaminan.nonaktif');
    Route::patch('/aktif/{id}', [JaminanController::class, 'aktif'])->name('jaminan.aktif');
});

Route::prefix('pengajuan_pinjaman')->middleware('web')->group(function () {
    Route::get('/', [PengajuanPinjamanController::class, 'index'])->name('pengajuanPinjaman.index');
    Route::get('/indexAnggota', [PengajuanPinjamanController::class, 'indexAnggota'])->name('pengajuanPinjaman.indexAnggota');
    Route::get('/create/{id}', [PengajuanPinjamanController::class, 'create'])->name('pengajuanPinjaman.create');
    Route::post('/store', [PengajuanPinjamanController::class, 'store'])->name('pengajuanPinjaman.store');
    Route::get('/edit/{id}', [PengajuanPinjamanController::class, 'edit'])->name('pengajuanPinjaman.edit');
    Route::put('/update/{id}', [PengajuanPinjamanController::class, 'update'])->name('pengajuanPinjaman.update');
    Route::delete('/delete/{id}', [PengajuanPinjamanController::class, 'destroy'])->name('pengajuanPinjaman.destroy');
    Route::get('/skema-pinjaman/{id}/detail', [PengajuanPinjamanController::class, 'detailSkema'])->name('pengajuanPinjaman.detailSkema');
    Route::patch('/updateStatus/{id}', [PengajuanPinjamanController::class, 'updateStatusVerifikasi'])->name('pengajuanPinjaman.updateStatus');
    Route::patch('/teruskan/{id}', [PengajuanPinjamanController::class, 'teruskan'])->name('pengajuanPinjaman.teruskan');
    Route::get('/cetak/{id}', [PengajuanPinjamanController::class, 'cetak'])->name('pengajuanPinjaman.cetak');
    Route::patch('/verifikasi/{id}', [PengajuanPinjamanController::class, 'verifikasi'])->name('pengajuanPinjaman.verifikasi');
    Route::patch('/tolak/{id}', [PengajuanPinjamanController::class, 'tolak'])->name('pengajuanPinjaman.tolak');
    Route::get('/revisi/{id}', [PengajuanPinjamanController::class, 'revisiJaminan'])->name('pengajuanPinjaman.revisi');
    Route::patch('/simpanRevisi/{id}', [PengajuanPinjamanController::class, 'simpanRevisi'])->name('pengajuanPinjaman.simpanRevisi');
});

Route::prefix('persetujuan')->middleware('web')->group(function () {
    Route::get('/', [PersetujuanController::class, 'index'])->name('persetujuan.index');
    Route::get('/indexAnggota', [PersetujuanController::class, 'indexAnggota'])->name('persetujuan.indexAnggota');
    Route::put('/setujui/{id}', [PersetujuanController::class, 'setujui'])->name('persetujuan.setujui');
    Route::put('/tolak/{id}', [PersetujuanController::class, 'tolak'])->name('persetujuan.tolak');
    Route::patch('/persetujuanAkhir/{id}', [PersetujuanController::class, 'persetujuanAkhir'])->name('persetujuan.persetujuanAkhir');
    Route::get('/pencairan', [PersetujuanController::class, 'indexPencairan'])->name('persetujuan.indexPencairan');
    Route::patch('/pencairan/{id}', [PersetujuanController::class, 'pencairan'])->name('persetujuan.pencairan');
});

Route::prefix('pinjaman')->middleware('web')->group(function () {
    Route::get('/', [PinjamanController::class, 'index'])->name('pinjaman.index');
    Route::get('/indexAnggota', [PinjamanController::class, 'indexAnggota'])->name('pinjaman.indexAnggota');
});

Route::prefix('angsuran')->middleware('web')->group(function () {
    Route::get('/', [AngsuranController::class, 'index'])->name('angsuran.index');
    Route::get('/index_verifikasi', [AngsuranController::class, 'indexVerifikasi'])->name('angsuran.indexVerifikasi');
    Route::get('/getAngsuran', [AngsuranController::class, 'getAngsuranByIdAnggota'])->name('angsuran.indexAnggota');
    Route::patch('/gagal_debet/{id}', [AngsuranController::class, 'gagalDebet'])->name('angsuran.gagal_debet');
    Route::get('/cetakDataTagihan', [AngsuranController::class, 'cetakDataTagihan'])->name('angsuran.cetakDataTagihan');
});

Route::prefix('pembayaran')->middleware('web')->group(function () {
    Route::post('/store_manual', [PembayaranController::class, 'storeManual'])->name('pembayaran.store_manual');
    Route::post('/store_ulang_manual', [PembayaranController::class, 'storeUlangManual'])->name('pembayaran.store_ulang_manual');
    Route::post('/store_auto_debet', [PembayaranController::class, 'storeAutoDebet'])->name('pembayaran.store_auto_debet');
    Route::get('/verifikasi', [PembayaranController::class, 'indexVerifikasi'])->name('pembayaran.indexVerifikasi');
    Route::patch('/verifikasi/{id}', [PembayaranController::class, 'update'])->name('pembayaran.verifikasi');
    Route::patch('/gagalVerifikasi/{id}', [PembayaranController::class, 'gagalUpdate'])->name('pembayaran.gagalVerifikasi');
});
