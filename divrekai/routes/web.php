<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PendapatanController;
use App\Http\Controllers\TargetPendapatanController;

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect('/login');
    }

    if (!auth()->user()->role) {
        abort(403, 'Role belum diatur');
    }

    return match (auth()->user()->role->username) {
        'admin_pic'  => redirect()->route('admin.dashboard'),
        'admin_unit' => redirect()->route('pendapatan.index'),
        'pimpinan'   => redirect()->route('pimpinan.dashboard'),
        default      => abort(403),
    };
});

/*
|--------------------------------------------------------------------------
| ADMIN PIC
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin_pic'])->group(function () {
    Route::get('/dashboard-admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

/*
|--------------------------------------------------------------------------
| ADMIN UNIT
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin_unit'])->group(function () {

    Route::get('/pendapatan', [PendapatanController::class, 'index'])
        ->name('pendapatan.index');

    Route::get('/pendapatan/input', [PendapatanController::class, 'create'])
        ->name('pendapatan.input');

    Route::post('/pendapatan', [PendapatanController::class, 'store'])
        ->name('pendapatan.store');

    Route::get('/pendapatan/{pendapatan}/edit', [PendapatanController::class, 'edit'])
        ->name('pendapatan.edit');

    Route::put('/pendapatan/{pendapatan}', [PendapatanController::class, 'update'])
        ->name('pendapatan.update');

    Route::delete('/pendapatan/{pendapatan}', [PendapatanController::class, 'destroy'])
        ->name('pendapatan.destroy');

    Route::post('/target', [TargetPendapatanController::class, 'store'])
        ->name('target.store');
});

/*
|--------------------------------------------------------------------------
| PIMPINAN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:pimpinan'])->group(function () {
    Route::get('/dashboard-pimpinan', function () {
        return view('pimpinan.dashboard');
    })->name('pimpinan.dashboard');
});

/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
