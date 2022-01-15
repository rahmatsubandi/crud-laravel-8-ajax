<?php

use App\Http\Controllers\EmployeController;
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

Route::get('/', [EmployeController::class, 'index']);
Route::post('/store', [EmployeController::class, 'store'])->name('store');
Route::get('/fetchall', [EmployeController::class, 'fetchAll'])->name('fetchAll');
Route::delete('/delete', [EmployeController::class, 'delete'])->name('delete');
Route::get('/edit', [EmployeController::class, 'edit'])->name('edit');
Route::post('/update', [EmployeController::class, 'update'])->name('update');
