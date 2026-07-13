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

Route::prefix('jadwal-simpanan')->middleware(['auth'])->group(function() {
    Route::get('/', 'MasterJenisSimpananController@index')->name('master-jenis-simpanan.index');
    Route::get('/master-jenis-simpanan/create', 'MasterJenisSimpananController@create')->name('master-jenis-simpanan.create');
    Route::post('/master-jenis-simpanan/store', 'MasterJenisSimpananController@store')->name('master-jenis-simpanan.store');
    Route::get('/master-jenis-simpanan/{id}', 'MasterJenisSimpananController@show')->name('master-jenis-simpanan.show');
    Route::put('/master-jenis-simpanan/updatedata/{id}', 'MasterJenisSimpananController@update')->name('master-jenis-simpanan.update'); 
});

Route::prefix('simpanan-sukarela')->middleware(['auth'])->group(function() {
    Route::get('/', 'SimpananSukarelaController@index')->name('simpanan-sukarela.index');
    Route::get('/create', 'SimpananSukarelaController@create')->name('simpanan-sukarela.create');
    Route::post('/store', 'SimpananSukarelaController@store')->name('simpanan-sukarela.store');
    Route::get('/{id}', 'SimpananSukarelaController@show')->name('simpanan-sukarela.show');
    Route::put('/{id}', 'SimpananSukarelaController@update')->name('simpanan-sukarela.update');
});

Route::prefix('simpanan-wajib')->middleware(['auth'])->group(function() {
    Route::get('/', 'SimpananWajibController@index')->name('simpanan-wajib.index');
    Route::get('/create', 'SimpananWajibController@create')->name('simpanan-wajib.create');
    Route::post('/store', 'SimpananWajibController@store')->name('simpanan-wajib.store');
    Route::get('/{id}', 'SimpananWajibController@show')->name('simpanan-wajib.show');
    Route::put('/{id}', 'SimpananWajibController@update')->name('simpanan-wajib.update');
});