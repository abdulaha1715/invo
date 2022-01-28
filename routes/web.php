<?php

use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

// Frontend
Route::get('/', function () {
    return view('welcome');
});


// Backend



Route::prefix('dashboard')->middleware(['auth'])->group(function () {

    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('client', ClientController::class);


});




require __DIR__.'/auth.php';
