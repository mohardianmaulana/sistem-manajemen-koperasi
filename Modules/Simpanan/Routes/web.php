<?php
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\RoleMiddleware;
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


Route::prefix('simpanan')->middleware(['auth'])->group(function() {
    Route::get('/', 'SimpananController@index')->name('simpanan-pokok.index');
    Route::get('/tambah', 'SimpananController@create')->name('simpanan-pokok.create');
    Route::post('/store', 'SimpananController@store')->name('simpanan-pokok.store');
    Route::get('/{id}', 'SimpananController@show')->name('simpanan-pokok.show');
    Route::put('/updatedata/{id}', 'SimpananController@update')->name('simpanan-pokok.update');
});

Route::prefix('jadwal-simpanan')->middleware(['auth', 'role:admin'])->group(function() {
    Route::get('/', 'MasterJenisSimpananController@index')->name('master-jenis-simpanan.index');
    Route::get('/master-jenis-simpanan/create', 'MasterJenisSimpananController@create')->name('master-jenis-simpanan.create');
    Route::post('/master-jenis-simpanan/store', 'MasterJenisSimpananController@store')->name('master-jenis-simpanan.store');
    Route::get('/master-jenis-simpanan/{id}', 'MasterJenisSimpananController@show')->name('master-jenis-simpanan.show');
    Route::put('/master-jenis-simpanan/updatedata/{id}', 'MasterJenisSimpananController@update')->name('master-jenis-simpanan.update'); 
});

Route::prefix('simpanan-sukarela')->middleware(['auth'])->group(function () {
        Route::get('/','SimpananSukarelaController@index')->name('simpanan-sukarela.index');
        Route::middleware(['role:anggota'])->group(function () {
            Route::get('/create','SimpananSukarelaController@create')->name('simpanan-sukarela.create');
            Route::post('/store','SimpananSukarelaController@store')->name('simpanan-sukarela.store');
            Route::get('/{id}','SimpananSukarelaController@show')->name('simpanan-sukarela.show');
            Route::get('/{id}/edit', 'SimpananSukarelaController@edit')->name('simpanan-sukarela.edit');
            Route::put('/{id}', 'SimpananSukarelaController@updatePengajuan')->name('simpanan-sukarela.update');
            Route::get('/{id}/upload-bukti', 'SimpananSukarelaController@uploadBuktiForm')->name('simpanan-sukarela.upload-bukti');
            Route::put('/{id}/upload-bukti', 'SimpananSukarelaController@uploadBukti')->name('simpanan-sukarela.upload-bukti.store');
        });
        Route::middleware(['role:admin'])->group(function () {
            Route::get('/export-auto-debit','SimpananSukarelaController@exportAutoDebit')->name('simpanan-sukarela.export-auto-debit');
            Route::get('/{id}/verifikasi', 'SimpananSukarelaController@verifikasi')->name('simpanan-sukarela.verifikasi');
            Route::put('/{id}/verifikasi', 'SimpananSukarelaController@updateStatus')->name('simpanan-sukarela.update-status');
        }); 
    });

Route::prefix('simpanan-wajib')->middleware(['auth'])->group(function () {
    Route::get('/', 'SimpananWajibController@index')
        ->name('simpanan-wajib.index');
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/create', 'SimpananWajibController@create')->name('simpanan-wajib.create');
        Route::post('/store', 'SimpananWajibController@store')->name('simpanan-wajib.store');
        Route::get('/export-auto-debit','SimpananWajibController@exportAutoDebit')->name('simpanan-wajib.export-auto-debit');
    });
    Route::get('/{id}', 'SimpananWajibController@show')->name('simpanan-wajib.show');
    Route::put('/{id}', 'SimpananWajibController@update')->name('simpanan-wajib.update');

});