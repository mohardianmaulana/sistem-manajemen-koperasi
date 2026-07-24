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
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/create', 'ShuAnggotaController@create')->name('shu.generate');
        Route::post('/hitung', 'ShuAnggotaController@store')->name('shu.store');
    });
});

Route::prefix('shu-koperasi')->middleware(['auth', 'role:admin'])->group(function() {
    Route::get('/', 'SHUController@index')->name('shu-koperasi.index');
    Route::get('/create', 'SHUController@create')->name('shu-koperasi.create');
    Route::post('/store', 'SHUController@store')->name('shu-koperasi.store');
    Route::get('/{id}', 'SHUController@show')->name('shu-koperasi.show');
    Route::put('/{id}/update', 'SHUController@update')->name('shu-koperasi.update');
});

Route::prefix('pencairan')->middleware(['auth', 'role:admin'])->group(function () {
    
    Route::put('/{id}/approve', 'PencairanController@approve')->name('pencairan.approve');
    Route::put('/{id}/reject', 'PencairanController@reject')->name('pencairan.reject');
    Route::put('/{id}/cairkan', 'PencairanController@cairkan')->name('pencairan.cairkan');
    Route::delete('/{id}/delete', 'PencairanController@destroy')->name('pencairan.destroy');
});
Route::prefix('pencairan')->middleware(['auth'])->group(function (){
    Route::get('/', 'PencairanController@index')->name('pencairan.index');
    Route::get('/{id}', 'PencairanController@show')->name('pengajuan-pencairan.edit');
});

Route::prefix('pengajuan-pencairan')->middleware(['auth', 'role:anggota'])->group(function () {
    Route::get('/create', 'PencairanController@create')->name('pengajuan-pencairan.index');
    Route::post('/store', 'PencairanController@store')->name('pengajuan-pencairan.store');
    Route::get('/pengajuan-pencairan/{id}/edit', 'PencairanController@edit')->name('pengajuan-pencairan.edit');
    Route::put('/pengajuan-pencairan/{id}', 'PencairanController@update')->name('pengajuan-pencairan.update');
});