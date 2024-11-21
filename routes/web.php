<?php 

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DisponibilidadController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReservaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Asegúrate de incluir esta línea

Route::get('/', function () {
    return view('welcome'); // Página principal
});

// Rutas de autenticación
Auth::routes();

Route::middleware(['auth'])->group(function () {

    Route::get('/services/create', function () {
        return view('services.create'); 
    })->name('services.create');
    
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/services', [ServiceController::class, 'index']);
    Route::resource('services', ServiceController::class);
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update'); 
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/disponibilidad', [DisponibilidadController::class, 'index'])->name('disponibilidad.index');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('reservas', ReservaController::class);
    Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.send');
    Route::put('reservas/cancel/{id}', [ReservaController::class, 'cancel'])->name('reservas.cancel');
    Route::get('/reservas/create', [ReservaController::class, 'create'])->name('reservas.create');
    Route::post('/reservas', [ReservaController::class, 'store'])->name('reservas.store');
    Route::delete('/services/{id}', [ServiceController::class, 'destroy'])->name('services.destroy');
    Route::get('/services/{id}', [ServiceController::class, 'show'])->name('services.show');
    Route::get('/home/available-slots', [ReservaController::class, 'getAvailableSlots'])->name('reservas.available-slots');
});



