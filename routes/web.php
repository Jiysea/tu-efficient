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
use App\Livewire\Coordinator\Assignments;
use App\Livewire\Coordinator\Submissions;
use App\Livewire\Coordinator\Forms;
use App\Livewire\Focal\ActivityLogs;
use App\Livewire\Focal\Dashboard;
use App\Livewire\Focal\Implementations;
use App\Livewire\Focal\UserManagement;
use App\Livewire\Login\FocalCoordinatorForm;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    if (Auth::check()) {
        if (Auth::user()->user_type === 'focal')
            return redirect()->route('focal.dashboard');
        else if (Auth::user()->user_type === 'coordinator')
            return redirect()->route('coordinator.assignments');
    } else
        return view('landing.focal');

})->name('login');

Route::get('/barangay', function () {
    if (Auth::check()) {
        if (Auth::user()->user_type === 'focal')
            return redirect()->route('focal.dashboard');
        else if (Auth::user()->user_type === 'coordinator')
            return redirect()->route('coordinator.assignments');
    } else
        return view('landing.barangay');
})->name('barangay');


// Route::post('/focal/login', [AuthenticatedSessionController::class, 'store'])->name('focal.login');
// Route::post('/barangay/check-access', [BarangayController::class, 'checkAccess'])->name('barangay.access');


// -------------------------------------


Route::middleware('auth')->group(function () {

    Route::get('/focal/dashboard', Dashboard::class)->name('focal.dashboard');
    Route::get('/focal/implementations', Implementations::class)->name('focal.implementations');
    Route::get('/focal/user-management', UserManagement::class)->name('focal.user-management');
    Route::get('/focal/activity-logs', ActivityLogs::class)->name('focal.activity-logs');

    Route::get('/coordinator/assignments', Assignments::class)->name('coordinator.assignments');
    Route::get('/coordinator/submissions/{batchId?}', Submissions::class)->name('coordinator.submissions');
    Route::get('/coordinator/forms', Forms::class)->name('coordinator.forms');

});

// Route::get('/barangay/{accessCode}', [BarangayIndexController::class, 'showAll'])->name('barangay.index');

// --------------------------------------

// Route::get('/focal/verify', FocalSMSVerificationController::class)->name('focal.verify');
// Route::get('/coordinator/verify', CoordinatorSMSVerificationController::class)->name('coordinator.verify');


// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__ . '/auth.php';
