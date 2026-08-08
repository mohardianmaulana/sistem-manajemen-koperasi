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

Route::prefix('shu')->middleware(['auth',])->group(function() {
    Route::get('/', 'ShuAnggotaController@index')->name('shu.index');
    Route::middleware(['role:koordinator'])->group(function () {
        Route::get('/create', 'ShuAnggotaController@create')->name('shu.generate');
        Route::post('/hitung', 'ShuAnggotaController@store')->name('shu.store');
    });
});

Route::prefix('shu-koperasi')->middleware(['auth', 'role:koordinator'])->group(function() {
    Route::get('/', 'SHUController@index')->name('shu-koperasi.index');
    Route::get('/create', 'SHUController@create')->name('shu-koperasi.create');
    Route::post('/store', 'SHUController@store')->name('shu-koperasi.store');
    Route::get('/{id}', 'SHUController@show')->name('shu-koperasi.show');
    Route::put('/{id}/update', 'SHUController@update')->name('shu-koperasi.update');
});


Route::prefix('pencairan')->middleware(['auth'])->group(function () {
    Route::get('/', 'PencairanController@index')->name('pencairan.index');
    Route::get('/{id}', 'PencairanController@show')->name('pencairan.show');
    });

Route::prefix('pencairan')->middleware(['auth', 'role:bendahara'])->group(function () {
    Route::post('/', 'PencairanController@store')->name('pencairan.store');
    Route::put('/{id}/cairkan', 'PencairanController@cairkan')->name('pencairan.cairkan');
    Route::put('/{id}/gagal', 'PencairanController@gagal')->name('pencairan.gagal');
    });