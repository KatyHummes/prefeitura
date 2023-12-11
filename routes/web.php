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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('pessoas', [PeopleController::class, 'index'])->name('peoples');
    Route::post('criar-pessoa', [PeopleController::class, 'store'])->name('store.people')->middleware([HandlePrecognitiveRequests::class]);

    Route::get('protocolos', [ProtocolController::class, 'index'])->name('protocols');
    Route::post('criar-protocolo', [ProtocolController::class, 'store'])->name('store.protocol')->middleware([HandlePrecognitiveRequests::class]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');