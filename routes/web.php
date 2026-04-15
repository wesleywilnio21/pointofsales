<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Redirect default dashboard from Breeze to POS
Route::get('/dashboard', function () {
    return redirect()->route('pos.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// Auth routes for all authenticated users (admin + staff)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // POS accessed by both
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
});

use App\Http\Controllers\ReportController;

// Admin routes only (Product Management & Reports)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('products', ProductController::class);
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/restock', [\App\Http\Controllers\StockEntryController::class, 'index'])->name('restock.index');
    Route::post('/restock', [\App\Http\Controllers\StockEntryController::class, 'store'])->name('restock.store');
    Route::resource('suppliers', \App\Http\Controllers\SupplierController::class);
    Route::resource('users', \App\Http\Controllers\UserController::class)->only(['index', 'store', 'update', 'destroy']);
});

require __DIR__.'/auth.php';
