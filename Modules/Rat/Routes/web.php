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

Route::prefix('rat')->middleware(['auth', 'role:bendahara'])->group(function () {
    Route::get('/','RatController@index')->name('rat.index');
    Route::get('/create','RatController@create')->name('rat.create');
    Route::post('/store','RatController@store')->name('rat.store');
    Route::get('/{id}/edit','RatController@edit')->name('rat.edit');
    Route::put('/{id}','RatController@update')->name('rat.update');
});
