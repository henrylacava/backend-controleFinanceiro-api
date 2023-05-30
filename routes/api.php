<?php

use App\Http\Controllers\ReceitasController;
use App\Http\Controllers\DespesaController;
use App\Http\Controllers\ResumoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(ReceitasController::class)->group(function () {
    Route::get('/receitas', 'index')->name('receitas.index');
    Route::post('/receitas', 'store')->name('receitas.store');
    Route::get('/receitas/{id}', 'show')->name('receitas.show');
    Route::put('/receitas/{id}', 'update')->name('receitas.update');
    Route::delete('/receitas/{id}', 'destroy')->name('receitas.destroy');
    Route::get('/receitas/{ano}/{mes}', 'showByYearAndMonth')->name('receitas.showByYearAndMonth');
});

Route::controller(DespesaController::class)->group(function () {
    Route::get('/despesas', 'index')->name('despesas.index');
    Route::post('/despesas', 'store')->name('despesas.store');
    Route::get('/despesas/{id}', 'show')->name('despesas.show');
    Route::put('/despesas/{id}', 'update')->name('despesas.update');
    Route::delete('/despesas/{id}', 'destroy')->name('despesas.destroy');
    Route::get('/despesas/{ano}/{mes}, showByYearAndMonth')->name('receitas.showByYearAndMonth');
});

Route::get('/resumo/{ano}/{mes}', [ResumoController::class, 'summaryMonth'])->name('resumo.summary');
