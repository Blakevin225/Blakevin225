<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonneController;

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

Route::get('/', [PersonneController::class, 'index'])->name('liste');

Route::get('/recupediter/{id}', [PersonneController::class, 'recupForEdit'])->name('recupediter');
Route::post('/editer/{id}', [PersonneController::class, 'edit'])->name('editer');
Route::get('/delete/{id}', [PersonneController::class, 'delete'])->name('delete');
Route::post('/addtype', [PersonneController::class, 'ajaxTypepersonne'])->name('addtype');

Route::post('/insert',
    [PersonneController::class, 'ajaxRequestPost'])
    ->name('insert');

    Route::post('/insertphp',
    [PersonneController::class, 'phpRequestPost'])
    ->name('insertphp');

    

    Route::get('/form',
    [PersonneController::class, 'form'])
    ->name('form');

Route::get('/liste', [PersonneController::class, 'index'])->name('liste');

