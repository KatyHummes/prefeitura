<?php

use App\Http\Controllers\ProtocolController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use App\Http\Controllers\PeopleController;

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


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Pessoas:
    Route::get('/', [PeopleController::class, 'index'])->name('peoples');
    Route::post('criar-pessoa', [PeopleController::class, 'store'])->name('store.people')->middleware([HandlePrecognitiveRequests::class]);
    Route::get('editar-pessoa/{id}', [PeopleController::class, 'edit'])->name('people.edit');
    Route::put('atualizar-pessoa/{id}', [PeopleController::class, 'update'])->name('people.update')->middleware([HandlePrecognitiveRequests::class]);
    Route::delete('excluir-pessoa/{id}', [PeopleController::class, 'delete'])->name('people.delete');

    // Protocolos:
    Route::get('protocolos', [ProtocolController::class, 'index'])->name('protocols');
    Route::post('criar-protocolo', [ProtocolController::class, 'store'])->name('store.protocol')->middleware([HandlePrecognitiveRequests::class]);
    Route::put('atualizar-protocolo/{id}', [ProtocolController::class, 'update'])->name('protocol.update')->middleware([HandlePrecognitiveRequests::class]);
    Route::delete('excluir-protocolo/{id}', [ProtocolController::class, 'delete'])->name('protocol.delete');
});