<?php

use App\Http\Controllers\InventoryController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('inventory')->controller(InventoryController::class)->group(function () {
    Route::get('', 'index')->name('inventory.index');
    Route::post('', 'store')->name('inventory.store');
    Route::get('{inventory}', 'show')->name('inventory.show');
    Route::put('{inventory}', 'update')->name('inventory.update');
    Route::delete('{inventory}', 'destroy')->name('inventory.destroy');
});
