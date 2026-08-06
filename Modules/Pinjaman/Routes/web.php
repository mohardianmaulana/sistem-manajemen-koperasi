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
use Modules\Pinjaman\Http\Controllers\web\TelegramController;
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

Route::post('/telegram/webhook', [TelegramController::class, 'webhook']);

Route::prefix('simulasi_pinjaman')->middleware('web')->group(function () {
    Route::get('/', [SimulasiPinjamanController::class, 'index'])->name('simulasiPinjaman.index')->middleware('role:anggota');
    Route::get('/{id}', [SimulasiPinjamanController::class, 'hasil'])->name('simulasiPinjaman.hasil')->middleware('role:anggota');
    Route::post('/hitung', [SimulasiPinjamanController::class, 'hitung'])->name('simulasi-pinjaman.hitung')->middleware('role:anggota');
});

Route::prefix('skema_pinjaman')->middleware('web')->group(function () {
    Route::get('/', [SkemaPinjamanController::class, 'index'])->name('skemaPinjaman.index')->middleware('role:koordinator');
    Route::get('/create', [SkemaPinjamanController::class, 'create'])->name('skemaPinjaman.create')->middleware('role:koordinator');
    Route::post('/store', [SkemaPinjamanController::class, 'store'])->name('skemaPinjaman.store')->middleware('role:koordinator');
    Route::get('/edit/{id}', [SkemaPinjamanController::class, 'edit'])->name('skemaPinjaman.edit')->middleware('role:koordinator');
    Route::put('/update/{id}', [SkemaPinjamanController::class, 'update'])->name('skemaPinjaman.update')->middleware('role:koordinator');
    Route::patch('/nonaktif/{id}', [SkemaPinjamanController::class, 'nonaktif'])->name('skemaPinjaman.nonaktif')->middleware('role:koordinator');
    Route::patch('/aktif/{id}', [SkemaPinjamanController::class, 'aktif'])->name('skemaPinjaman.aktif')->middleware('role:koordinator');
});

Route::prefix('jaminan')->middleware('web')->group(function () {
    Route::get('/index', [JaminanController::class, 'index'])->name('jaminan.index')->middleware('role:koordinator');
    Route::get('/create', [JaminanController::class, 'create'])->name('jaminan.create')->middleware('role:koordinator');
    Route::post('/store', [JaminanController::class, 'store'])->name('jaminan.store')->middleware('role:koordinator');
    Route::get('/edit/{id}', [JaminanController::class, 'edit'])->name('jaminan.edit')->middleware('role:koordinator');
    Route::put('/update/{id}', [JaminanController::class, 'update'])->name('jaminan.update')->middleware('role:koordinator');
    Route::patch('/nonaktif/{id}', [JaminanController::class, 'nonaktif'])->name('jaminan.nonaktif')->middleware('role:koordinator');
    Route::patch('/aktif/{id}', [JaminanController::class, 'aktif'])->name('jaminan.aktif')->middleware('role:koordinator');
});

Route::prefix('pengajuan_pinjaman')->middleware('web')->group(function () {
    Route::get('/', [PengajuanPinjamanController::class, 'index'])->name('pengajuanPinjaman.index')->middleware('role:koordinator');
    Route::get('/indexAnggota', [PengajuanPinjamanController::class, 'indexAnggota'])->name('pengajuanPinjaman.indexAnggota')->middleware('role:anggota');
    Route::get('/create/{id}', [PengajuanPinjamanController::class, 'create'])->name('pengajuanPinjaman.create')->middleware('role:anggota');
    Route::post('/store', [PengajuanPinjamanController::class, 'store'])->name('pengajuanPinjaman.store')->middleware('role:anggota');
    Route::get('/edit/{id}', [PengajuanPinjamanController::class, 'edit'])->name('pengajuanPinjaman.edit')->middleware('role:anggota');
    Route::put('/update/{id}', [PengajuanPinjamanController::class, 'update'])->name('pengajuanPinjaman.update')->middleware('role:anggota');
    Route::delete('/delete/{id}', [PengajuanPinjamanController::class, 'destroy'])->name('pengajuanPinjaman.destroy')->middleware('role:anggota');
    Route::patch('/updateStatus/{id}', [PengajuanPinjamanController::class, 'updateStatusVerifikasi'])->name('pengajuanPinjaman.updateStatus')->middleware('role:koordinator');
    Route::patch('/teruskan/{id}', [PengajuanPinjamanController::class, 'teruskan'])->name('pengajuanPinjaman.teruskan')->middleware('role:koordinator');
    Route::get('/cetak/{id}', [PengajuanPinjamanController::class, 'cetak'])->name('pengajuanPinjaman.cetak')->middleware('role:koordinator|anggota');
    Route::patch('/verifikasi/{id}', [PengajuanPinjamanController::class, 'verifikasi'])->name('pengajuanPinjaman.verifikasi')->middleware('role:koordinator');
    Route::patch('/tolak/{id}', [PengajuanPinjamanController::class, 'tolak'])->name('pengajuanPinjaman.tolak')->middleware('role:koordinator');
    Route::get('/revisi/{id}', [PengajuanPinjamanController::class, 'revisiJaminan'])->name('pengajuanPinjaman.revisi')->middleware('role:anggota');
    Route::patch('/simpanRevisi/{id}', [PengajuanPinjamanController::class, 'simpanRevisi'])->name('pengajuanPinjaman.simpanRevisi')->middleware('role:anggota');
});

Route::prefix('persetujuan')->middleware('web')->group(function () {
    Route::get('/', [PersetujuanController::class, 'index'])->name('persetujuan.index')->middleware('role:ketua');
    Route::put('/setujui/{id}', [PersetujuanController::class, 'setujui'])->name('persetujuan.setujui')->middleware('role:ketua');
    Route::put('/tolak/{id}', [PersetujuanController::class, 'tolak'])->name('persetujuan.tolak')->middleware('role:ketua');
    Route::patch('/persetujuanAkhir/{id}', [PersetujuanController::class, 'persetujuanAkhir'])->name('persetujuan.persetujuanAkhir')->middleware('role:koordinator');
    Route::get('/pencairan', [PersetujuanController::class, 'indexPencairan'])->name('persetujuan.indexPencairan')->middleware('role:bendahara');
    Route::patch('/pencairan/{id}', [PersetujuanController::class, 'pencairan'])->name('persetujuan.pencairan')->middleware('role:bendahara');
});

Route::prefix('pinjaman')->middleware('web')->group(function () {
    Route::get('/', [PinjamanController::class, 'index'])->name('pinjaman.index')->middleware('role:ketua|bendahara|koordinator');
    Route::get('/indexAnggota', [PinjamanController::class, 'indexAnggota'])->name('pinjaman.indexAnggota')->middleware('role:anggota');
});

Route::prefix('angsuran')->middleware('web')->group(function () {
    Route::get('/', [AngsuranController::class, 'index'])->name('angsuran.index')->middleware('role:koordinator');
    Route::get('/getAngsuran', [AngsuranController::class, 'getAngsuranByIdAnggota'])->name('angsuran.indexAnggota')->middleware('role:anggota');
    Route::patch('/gagal_debet/{id}', [AngsuranController::class, 'gagalDebet'])->name('angsuran.gagal_debet')->middleware('role:koordinator');
    Route::get('/cetakDataTagihan', [AngsuranController::class, 'cetakDataTagihan'])->name('angsuran.cetakDataTagihan')->middleware('role:koordinator');
});

Route::prefix('pembayaran')->middleware('web')->group(function () {
    Route::post('/store_manual', [PembayaranController::class, 'storeManual'])->name('pembayaran.store_manual')->middleware('role:anggota');
    Route::post('/store_ulang_manual', [PembayaranController::class, 'storeUlangManual'])->name('pembayaran.store_ulang_manual')->middleware('role:anggota');
    Route::post('/store_auto_debet', [PembayaranController::class, 'storeAutoDebet'])->name('pembayaran.store_auto_debet')->middleware('role:koordinator');
    Route::get('/verifikasi', [PembayaranController::class, 'indexVerifikasi'])->name('pembayaran.indexVerifikasi')->middleware('role:koordinator');
    Route::patch('/verifikasi/{id}', [PembayaranController::class, 'update'])->name('pembayaran.verifikasi')->middleware('role:koordinator');
    Route::patch('/gagalVerifikasi/{id}', [PembayaranController::class, 'gagalUpdate'])->name('pembayaran.gagalVerifikasi')->middleware('role:koordinator');
});
