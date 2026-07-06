<?php
use Illuminate\Support\Facades\Route;
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

Route::prefix('shu')->group(function() {
    Route::get('/', 'ShuAnggotaController@index')->name('shu.index');
    Route::post('/hitung', 'ShuAnggotaController@store')->name('shu.store');
});
