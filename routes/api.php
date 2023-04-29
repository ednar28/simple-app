<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();

    Route::prefix('user-salesman')
        ->controller(UserSalesmanController::class)
        ->group(function () {
            Route::get('', 'index')->name('user.salesman.index');
            Route::post('', 'store')->name('user.salesman.store');
            Route::get('{user}', 'show')->name('user.salesman.show');
            Route::put('{user}', 'update')->name('user.salesman.update');
            Route::delete('{user}', 'destroy')->name('user.salesman.destroy');
        });
});
