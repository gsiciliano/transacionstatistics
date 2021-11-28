<?php

use Illuminate\Support\Facades\Route;

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

Route::get('statistics', '\App\Http\Controllers\TransactionController@index')
                                                            ->name('statistics.index');

Route::middleware('passport')->group(function(){
    Route::get('transactions', '\App\Http\Controllers\TransactionController@index')
                                                            ->name('transaction.index');
    Route::post('transactions', '\App\Http\Controllers\TransactionController@store')
                                                            ->name('transaction.store');
    Route::delete('transactions', '\App\Http\Controllers\TransactionController@destroy')
                                                            ->name('transaction.store');
});
