<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register.post')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Landing page - redirect to admin or show info
Route::get('/', function () {
    return redirect()->route('admin.kegiatan.index');
});

// Admin Routes - Kegiatan Management
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::resource('kegiatan', KegiatanController::class)->names([
        'index' => 'kegiatan.index',
        'create' => 'kegiatan.create',
        'store' => 'kegiatan.store',
        'show' => 'kegiatan.show',
        'edit' => 'kegiatan.edit',
        'update' => 'kegiatan.update',
        'destroy' => 'kegiatan.destroy',
    ]);

    // Additional routes for kegiatan
    Route::post('kegiatan/{kegiatan}/regenerate-token', [KegiatanController::class, 'regenerateToken'])
        ->name('kegiatan.regenerate-token');
    Route::get('kegiatan/{kegiatan}/absensi', [KegiatanController::class, 'absensi'])
        ->name('kegiatan.absensi');
    Route::get('kegiatan/{kegiatan}/export-excel', [KegiatanController::class, 'exportExcel'])
        ->name('kegiatan.export-excel');
    Route::get('kegiatan/{kegiatan}/qrcode', [KegiatanController::class, 'qrcode'])
        ->name('kegiatan.qrcode');
});

// Public Attendance Routes - put specific routes BEFORE dynamic {token} routes
Route::prefix('absensi')->name('absensi.')->group(function () {
    Route::get('/success', [AbsensiController::class, 'success'])->name('success');
    Route::get('/{token}', [AbsensiController::class, 'index'])->name('index');
    Route::post('/{token}', [AbsensiController::class, 'store'])->name('store');
});

// Error page for invalid token
Route::get('/invalid-token', [AbsensiController::class, 'invalidToken'])->name('invalid-token');
