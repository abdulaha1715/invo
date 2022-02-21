<?php

use App\Http\Controllers\api\NewController;
use App\Http\Controllers\api\testController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\TaskController;
use App\Mail\InvoiceEmail;
use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// Backend
Route::prefix('/')->middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('dashboard', function () {

        $user = User::find(Auth::user()->id);

        return view('dashboard')->with([
            'user'              => $user,
            'pending_tasks'     => $user->tasks->where('status','pending'),
            'unpaid_invoices'   => $user->invoices->where('status','unpaid'),
            'paid_invoices'   => $user->invoices->where('status','paid'),
        ]);
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

    Route::get('settings', function () {
        return view('settings');
    })->name('settings');

});

// Auth Routes
require __DIR__ . '/auth.php';
