<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ItemController;
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


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';

Route::get('/', [ItemController::class, 'index'])->name('/');
Route::get('/item/{item}', [ItemController::class, 'show']);

Route::post('/cart', [CartController::class, 'store']);
Route::get('/cartitem', [CartController::class, 'index']);
Route::delete('/cartitem/{cartitem}', [CartController::class, 'destroy']);
Route::put('/cartitem/{cartitem}', [CartController::class, 'update']);

Route::post('/cartitem', [CartController::class, 'sendMail']);
