<?php

use App\Http\Controllers\api\NewController;
use App\Http\Controllers\api\testController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\TaskController;
use App\Mail\InvoiceEmail;
use Illuminate\Support\Facades\Route;


// Backend
Route::prefix('/')->middleware(['auth'])->group(function () {

    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Client Route
    Route::resource('client', ClientController::class);

    // Task Route
    Route::resource('task', TaskController::class);
    Route::put('task/{task}/complete', [TaskController::class, 'markAsCcomplete'])->name('markAsCcomplete');

    // Invoices Route
    Route::prefix('invoices')->group(function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('invoice.index');
        Route::get('create', [InvoiceController::class, 'create'])->name('invoice.create');
        Route::put('{invoice}/update', [InvoiceController::class, 'update'])->name('invoice.update');
        Route::delete('{invoice}/delete', [InvoiceController::class, 'destroy'])->name('invoice.destroy');
        Route::get('inovice', [InvoiceController::class, 'inovice'])->name('inovice');
        Route::get('email/send/{invoice:invoice_id}', [InvoiceController::class, 'sendEmail'])->name('invoice.sendEmail');
    });
});

// Auth Routes
require __DIR__ . '/auth.php';
