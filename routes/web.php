<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('/collection')->group(function() {
    Route::controller(ArticleController::class)->group(function() {
        Route::get('/numbers', 'numbers')->name('collection.numbers');
        Route::get('/whereClauses', 'whereClauses')->name('collection.whereClauses');
        Route::get('/mapMethod', 'mapMethod')->name('collection.mapMethod');
        Route::get('/conExOn', 'conExOn')->name('collection.conExOn');
    });
});
