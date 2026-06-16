<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Pinjaman\Http\Controllers\AngsuranApiController;
use Modules\Pinjaman\Http\Controllers\api\PengajuanPinjamanApiController;
use Modules\Pinjaman\Http\Controllers\api\SkemaPinjamanApiController;
use Modules\Pinjaman\Http\Controllers\PembayaranApiController;
use Modules\Pinjaman\Http\Controllers\PersetujuanApiController;
use Modules\Pinjaman\Http\Controllers\PinjamanApiController;
use Modules\Pinjaman\Http\Controllers\web\SimulasiPinjamanController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/pinjaman', function (Request $request) {
    return $request->user();
});

Route::apiResource('skema-pinjaman', SkemaPinjamanApiController::class);

Route::prefix('skema_pinjaman')->middleware('api')->group(function () {
    Route::get('/', [SkemaPinjamanApiController::class, 'index']);
    Route::post('/store', [SkemaPinjamanApiController::class, 'store']);
    Route::get('/show/{$id}', [SkemaPinjamanApiController::class, 'show']);
    Route::post('/update/{$id}', [SkemaPinjamanApiController::class, 'update']);
    Route::post('/delete/{$id}', [SkemaPinjamanApiController::class, 'destroy']);
});

Route::prefix('pengajuan_pinjaman')->middleware('api')->group(function () {
    Route::get('/', [PengajuanPinjamanApiController::class, 'index']);
    Route::get('/indexAnggota', [PengajuanPinjamanApiController::class, 'indexAnggota']);
    Route::post('/store', [PengajuanPinjamanApiController::class, 'store']);
    Route::get('/show/{$id}', [PengajuanPinjamanApiController::class, 'show']);
    Route::post('/update/{$id}', [PengajuanPinjamanApiController::class, 'update']);
    Route::post('/teruskan/{$id}', [PengajuanPinjamanApiController::class, 'teruskan']);
});

Route::prefix('persetujuan')->middleware('api')->group(function () {
    Route::get('/', [PersetujuanApiController::class, 'index']);
    Route::get('/indexAnggota', [PersetujuanApiController::class, 'indexAnggota']);
    Route::get('/show/{$id}', [PersetujuanApiController::class, 'show']);
    Route::post('/setujui/{$id}', [PersetujuanApiController::class, 'setujui']);
    Route::post('/tolak/{$id}', [PersetujuanApiController::class, 'tolak']);
});

Route::prefix('pinjaman')->middleware('api')->group(function () {
    Route::get('/', [PinjamanApiController::class, 'index']);
    Route::get('/show/{$id}', [PinjamanApiController::class, 'show']);
});

Route::prefix('angsuran')->middleware('api')->group(function () {
    Route::get('/', [AngsuranApiController::class, 'index']);
    Route::get('/getAngsuran/{$id}', [AngsuranApiController::class, 'getAngsuranByIdAnggota']);
});

Route::prefix('pembayaran')->middleware('api')->group(function () {
    Route::get('/show/{$id}', [PembayaranApiController::class, 'show']);
    Route::post('/store_manual', [PembayaranApiController::class, 'storeManual']);
    Route::post('/store_auto_debet', [PembayaranApiController::class, 'storeAutoDebet']);
    Route::post('/verifikasi', [PembayaranApiController::class, 'update']);
});