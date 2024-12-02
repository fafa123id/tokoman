<?php

use App\Http\Controllers\barangController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\ProfitController;
use Illuminate\Support\Facades\Route;


Route::get('/theAPI', [barangController::class, 'apiRecieve'])->middleware('auth')->name('theAPI');
//API Profit
 Route::get("/api", [ProfitController::class, "apiFetch"])->middleware('auth')->name("ApiFetch");
//API Penjualan
 Route::get("/api/sales", [dashboardController::class, "apiFetch"])->middleware('auth')->name("ApiSales");
 Route::post("/testingAPI123", [barangController::class, 'apiSeeder'])->name('testingAPI');