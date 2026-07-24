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

Route::prefix('unit')->middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/', 'UnitController@index')->name('unit.index');
        Route::get('/create', 'UnitController@create')->name('unit.create');
        Route::post('/store', 'UnitController@store')->name('unit.store');
        Route::get('/{id}', 'UnitController@show')->name('unit.show');
        Route::get('/{id}/edit', 'UnitController@edit')->name('unit.edit');
        Route::put('/{id}', 'UnitController@update')->name('unit.update');
        Route::delete('/{id}', 'UnitController@destroy')->name('unit.destroy');
    });