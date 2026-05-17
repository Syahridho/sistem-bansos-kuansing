<?php

use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\AssistanceTypeController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\PeriodeBantuanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SpkController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CekBantuanController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Cek Bantuan (Public)
Route::get('/cek-bantuan', [CekBantuanController::class, 'index'])->name('cek-bantuan.index');
Route::post('/cek-bantuan', [CekBantuanController::class, 'search'])->name('cek-bantuan.search');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/calender', [CalendarController::class, 'index'])->name('calender.index');
    Route::get('/calender/events', [CalendarController::class, 'events'])->name('calender.events');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Periode Bantuan — Admin & Operator
Route::middleware(['auth', 'role:admin,operator'])->group(function () {
    Route::resource('periode', PeriodeBantuanController::class)->parameters(['periode' => 'periode']);
    Route::patch('periode/{periode}/toggle-status', [PeriodeBantuanController::class, 'toggleStatus'])->name('periode.toggle-status');

    // Alternatif nested under Periode
    Route::post('periode/{periode}/import', [PeriodeBantuanController::class, 'import'])->name('periode.import');
    Route::get('periode/{periode}/alternatif/create', [AlternatifController::class, 'create'])->name('alternatif.create');
    Route::post('periode/{periode}/alternatif', [AlternatifController::class, 'store'])->name('alternatif.store');
    Route::get('periode/{periode}/alternatif/{alternatif}/edit', [AlternatifController::class, 'edit'])->name('alternatif.edit');
    Route::put('periode/{periode}/alternatif/{alternatif}', [AlternatifController::class, 'update'])->name('alternatif.update');
    Route::delete('periode/{periode}/alternatif/{alternatif}', [AlternatifController::class, 'destroy'])->name('alternatif.destroy');
});

// Perangkingan WP per Periode — semua user
Route::middleware(['auth'])->group(function () {
    Route::get('periode/{periode}/perangkingan', [SpkController::class, 'hitungWP'])->name('spk.hasil');
    Route::get('periode/{periode}/perangkingan/export', [SpkController::class, 'export'])->name('perangkingan.export');
});

// Kriteria & Assistance Type — Admin only
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('assistance-types', AssistanceTypeController::class)->names('assistance_types')->except(['show']);
    Route::resource('kriteria', KriteriaController::class)->parameters(['kriteria' => 'kriteria']);
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');

    // User Management
    Route::get('dashboard/users', [\App\Http\Controllers\UserManagementController::class, 'index'])->name('users.index');
    Route::post('dashboard/users/{user}/role', [\App\Http\Controllers\UserManagementController::class, 'updateRole'])->name('users.updateRole');
    Route::post('dashboard/users/{user}/toggle-status', [\App\Http\Controllers\UserManagementController::class, 'toggleStatus'])->name('users.toggleStatus');
});

require __DIR__.'/auth.php';
