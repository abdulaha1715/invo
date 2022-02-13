<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;


// Backend

Route::prefix('/')->middleware(['auth'])->group(function () {

    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Client Route
    Route::resource('client', ClientController::class);

    // Tasks By Client
    Route::get('client/{client:username}/tasks', [ClientController::class, 'searchTaskByClient'])->name('searchTaskByClient');

    // Task Route
    Route::resource('task', TaskController::class);
    Route::put('task/{task}/complete', [TaskController::class, 'markAsCcomplete'])->name('markAsCcomplete');


    // Invoices Route
    Route::prefix('invoices')->group(function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('invoice.index');
        Route::get('create', [InvoiceController::class, 'create'])->name('invoice.create');
        Route::put('{invoice}/update', [InvoiceController::class, 'update'])->name('invoice.update');
        Route::delete('{invoice}/delete', [InvoiceController::class, 'destroy'])->name('invoice.destroy');
        Route::get('preview', [InvoiceController::class, 'preview'])->name('preview.invoice');
        Route::get('generate', [InvoiceController::class, 'generate'])->name('invoice.generate');
        Route::get('email/send/{invoice:invoice_id}', [InvoiceController::class, 'sendEmail'])->name('invoice.sendEmail');
    });

});




require __DIR__ . '/auth.php';
