<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DisponibilidadController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReservaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// Rutas de autenticación
Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/services', [ServiceController::class, 'index']);
    Route::resource('services', ServiceController::class);
    Route::post('services', [ServiceController::class, 'store'])->name('services.store');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/disponibilidad', [DisponibilidadController::class, 'index'])->name('disponibilidad.index');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('reservas', ReservaController::class);
    Route::delete('/reservas/{id}/cancel', [ReservaController::class, 'cancel'])->name('reservas.cancel');
    Route::get('reservas/{id}/edit', [ReservaController::class, 'edit'])->name('reservas.edit');
    Route::put('reservas/{id}/update', [ReservaController::class, 'update'])->name('reservas.update');
    Route::get('/reservas/create', [ReservaController::class, 'create'])->name('reservas.create');
    Route::post('/reservas', [ReservaController::class, 'store'])->name('reservas.store');
    Route::get('/home/available-slots', [ReservaController::class, 'getAvailableSlots'])->name('reservas.available-slots');
});
