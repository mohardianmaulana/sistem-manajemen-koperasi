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

Route::prefix('user')->middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/', 'UserController@index')->name('user.index');
        Route::get('/create', 'UserController@create')->name('user.create');
        Route::post('/', 'UserController@store')->name('user.store');
        Route::get('/{id}/edit', 'UserController@edit') ->name('user.edit');
        Route::put('/{id}', 'UserController@update')->name('user.update');
        Route::delete('/{id}', 'UserController@destroy')->name('user.destroy');

    });
