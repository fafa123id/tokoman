<?php

use App\Http\Controllers\agentsController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\mitraController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\barangController;
use App\Http\Controllers\SearchStok;
use App\Http\Controllers\pegawaiController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfitController;
use App\Http\Controllers\riwayatController;
use App\Http\Controllers\welcomeController;
use Illuminate\Support\Facades\Route;


// Route untuk user terAuth
Route::middleware(['auth', 'auth.session', 'verifypls', 'noback','edited'])->group(function () {
    //Agents Mitra
    Route::get('/permissionAPI/{id}', [barangController::class, 'channelRecieve'])->name('APIPermission');
    Route::get('/getAuthID', [pegawaiController::class, 'getAuthID'])->name('getAuthID');
    Route::put('/TokenEdits',[pegawaiController::class, 'newToken'])->middleware('greateradmin')->name('newToken');
    Route::prefix('agents')->group(function () {
        Route::name('agents.')->group(function () {
            Route::get('/', [agentsController::class, 'index'])->name('index');
            Route::get('/add', [agentsController::class, 'add'])->name('add');
            Route::post('/', [AgentsController::class, 'store'])->name('store');
            Route::get('/{agents}', [AgentsController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [AgentsController::class, 'edit'])->name('edit');
            Route::put('/{agents}', [AgentsController::class, 'update'])->name('update');
            Route::delete('/{agents}', [AgentsController::class, 'destroy'])->name('destroy');
        });
    });

    Route::prefix('mitra')->group(function () {
        Route::name('mitra.')->group(function () {
            Route::get('/', [mitraController::class, 'index'])->name('index');
            Route::get('/add', [mitraController::class, 'add'])->name('add');
            Route::post('/', [mitraController::class, 'store'])->name('store');
            Route::get('/{mitra}', [mitraController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [mitraController::class, 'edit'])->name('edit');
            Route::put('/{mitra}', [mitraController::class, 'update'])->name('update');
            Route::delete('/{mitra}', [mitraController::class, 'destroy'])->name('destroy');
        });
    });

    Route::post('/export-profit', [dashboardController::class, 'exportPDF'])->name('exportProfit');
    Route::post('/export-pdf', [riwayatController::class, 'exportPDF'])->name('exportPDF');

    // Route Semua Role User
    Route::prefix('riwayat')->group(function () {
        Route::get('/', [riwayatController::class, 'index'])->name('riwayat');
        Route::get('/filter', [riwayatController::class, 'filterResults'])->name('riwayatFilter');
    });

    Route::get('/dashboard', [dashboardController::class, 'index'])->name('dashboard');

    // Laporan dan profit
    Route::get('/pelaporan', [LaporanController::class, 'index'])->name('laporan');
    Route::get('/hitungProfit', [ProfitController::class, 'index'])->name('profit.index');
    Route::post('/pelaporan', [LaporanController::class, 'laporanPost'])->name('pelaporan.postData');

    // Manipulasi Stok
    Route::prefix('stok')->group(function () {
        Route::name('stok.')->group(function () {
            Route::get('/', [barangController::class, 'index'])->name('index');
            Route::get('/search', [SearchStok::class, 'index'])->name('search');
            Route::get('/add', [barangController::class, 'add'])->name('tambah');
            Route::post('/addsave', [barangController::class, 'addsave'])->name('save');
            Route::get('/edit/{id}', [barangController::class, 'edit'])->name('edit');
            Route::put('/editsave/{id}', [barangController::class, 'editsave'])->name('editSave');
            Route::delete('/delete/{id}', [barangController::class, 'delete'])->name('delete');
        });
        Route::get('/addsave', function () {
            return redirect()->route('stokBarang');
        });
        Route::get('/editsave/{id}', function () {
            return redirect()->route('stokBarang');
        });
        Route::get('/delete/{id}', function () {
            return redirect()->route('stokBarang');
        });

    });

    Route::put('/tambahstok/{id}', [barangController::class, 'tambahStok'])->name('tambahStok');
    Route::delete('/deleteImg/{id}', [barangController::class, 'deleteImg'])->name('deletegambar');

    // Mentalin Request Post saat direquest Get
    Route::get('/tambahstok/{id}', function () {
        return redirect()->route('stokBarang');
    });
    Route::get('/deleteImg/{id}', function () {
        return redirect()->route('stokBarang');
    });

    // Khusus Admin
    Route::prefix('admin')->middleware(['admin'])->group(function () {
        Route::name('admin.')->group(function () {
            Route::controller(pegawaiController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/add', 'add')->name('add');
                Route::post('/addsave', 'addSave')->name('addSave');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::put('/editsave/{id}', 'editsave')->name('editSave');
                Route::delete('/delete/{id}',  'delete')->name('delete');
            });
        });

        Route::get('/editsave/{id}', function () {
            return redirect()->route('Manajemen.Admin');
        });
        Route::get('/delete/{id}', function () {
            return redirect()->route('Manajemen.Admin');
        });
        Route::get('/addsave', function () {
            return redirect()->route('Manajemen.Admin');
        });
    });
});

// Route Guest unAuth()
Route::get('/', [welcomeController::class, 'index'])->middleware(['noback','auth.session','edited'])->name('stokBarang');
Route::get('/wa/{id}', [welcomeController::class, 'reqWa']);
// RouteSearch
Route::get('/search', [SearchController::class, 'index'])->name('search')->middleware(['noback','auth.session','edited']);

Route::middleware(['auth', 'auth.session', 'noback','edited'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

require __DIR__ . '/api.php';