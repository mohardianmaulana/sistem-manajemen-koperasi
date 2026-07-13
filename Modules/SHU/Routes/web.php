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
        Route::post('/hitung', 'ShuAnggotaController@store')
            ->name('shu.store');
    });
});

Route::prefix('shu-koperasi')->middleware(['auth', 'role:admin'])->group(function() {
    Route::get('/', 'SHUController@index')->name('shu-koperasi.index');
    Route::get('/create', 'SHUController@create')->name('shu-koperasi.create');
    Route::post('/store', 'SHUController@store')->name('shu-koperasi.store');
    Route::get('/{id}', 'SHUController@show')->name('shu-koperasi.show');
    Route::put('/{id}/update', 'SHUController@update')->name('shu-koperasi.update');
});