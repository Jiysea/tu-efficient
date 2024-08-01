<?php

use App\Http\Controllers\BarangayController;
use App\Http\Controllers\BarangayIndexController;
use App\Http\Controllers\CoordinatorController;
use App\Http\Controllers\CoordinatorIndexController;
use App\Http\Controllers\CoordinatorSMSVerificationController;
use App\Http\Controllers\FocalController;
use App\Http\Controllers\FocalIndexController;
use App\Http\Controllers\FocalSMSVerificationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', FocalController::class)->name('login');
Route::get('/coordinator', CoordinatorController::class)->name('coordinator');
Route::get('/barangay', BarangayController::class)->name('barangay');


// Route::post('/focal/login', [FocalController::class, 'authenticate'])->name('focal.login');

Route::middleware('auth')->group(function () {

    Route::get('/focal/home', FocalIndexController::class)->name('focal.dashboard');
    Route::get('/coordinator/home', CoordinatorIndexController::class)->name('coordinator.home');

});

Route::get('/barangay/details', [BarangayIndexController::class, 'showAll'])->name('barangay.index');



// Route::get('/focal/verify', FocalSMSVerificationController::class)->name('focal.verify');
// Route::get('/coordinator/verify', CoordinatorSMSVerificationController::class)->name('coordinator.verify');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
