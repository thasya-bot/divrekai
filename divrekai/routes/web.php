<?php

use App\Http\Controllers\AdminPIC\PendapatanUnitController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PendapatanController;
use App\Http\Controllers\TargetPendapatanController;
use App\Http\Controllers\AdminPIC\UnitController;
use App\Http\Controllers\AdminPIC\UserController;

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
        'admin_pic'  => redirect()->route('admin.pic.unit.index'),
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
Route::middleware(['auth', 'role:admin_pic'])
    ->prefix('admin-pic')
    ->name('admin.pic.')
    ->group(function () {

        Route::get('/unit', [UnitController::class, 'index'])
            ->name('unit.index');

        Route::get('/unit/{unit}/pendapatan',
            [PendapatanUnitController::class, 'index'])
            ->name('unit.pendapatan');

        Route::get('/pendapatan/{pendapatan}/edit',
            [PendapatanUnitController::class, 'edit'])
            ->name('pendapatan.edit');

        Route::put('/pendapatan/{pendapatan}',
            [PendapatanUnitController::class, 'update'])
            ->name('pendapatan.update');

        Route::delete('/pendapatan/{pendapatan}',
            [PendapatanUnitController::class, 'destroy'])
            ->name('pendapatan.destroy');

             Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        Route::get('/users/create', [UserController::class, 'create'])
            ->name('users.create');

        Route::post('/users', [UserController::class, 'store'])
            ->name('users.store');

        Route::get('/users/{user}/edit', [UserController::class, 'edit'])
            ->name('users.edit');

        Route::put('/users/{user}', [UserController::class, 'update'])
            ->name('users.update');

        Route::delete('/users/{user}', [UserController::class, 'destroy'])
            ->name('users.destroy');
    });

/*
|--------------------------------------------------------------------------
| ADMIN UNIT
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin_unit'])->group(function () {

    // DASHBOARD / LAPORAN
    Route::get('/pendapatan', [PendapatanController::class, 'index'])
        ->name('pendapatan.index');

    // FORM INPUT PENDAPATAN
    Route::get('/pendapatan/input', [PendapatanController::class, 'create'])
        ->name('pendapatan.input');

    // SIMPAN PENDAPATAN
    Route::post('/pendapatan', [PendapatanController::class, 'store'])
        ->name('pendapatan.store');

    // EDIT
    Route::get('/pendapatan/{pendapatan}/edit', [PendapatanController::class, 'edit'])
        ->name('pendapatan.edit');

    // UPDATE
    Route::put('/pendapatan/{pendapatan}', [PendapatanController::class, 'update'])
        ->name('pendapatan.update');

    // DELETE
    Route::delete('/pendapatan/{pendapatan}', [PendapatanController::class, 'destroy'])
        ->name('pendapatan.destroy');

    // TARGET PENDAPATAN
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

require __DIR__.'/auth.php';