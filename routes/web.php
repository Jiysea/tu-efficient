<?php

use App\Http\Controllers\BarangayController;
use App\Livewire\Coordinator\Assignments;
use App\Livewire\Coordinator\Submissions;
use App\Livewire\Coordinator\Forms;
use App\Livewire\Focal\ActivityLogs;
use App\Livewire\Focal\Dashboard;
use App\Livewire\Focal\Implementations;
use App\Livewire\Focal\Settings;
use App\Livewire\Focal\UserManagement;
use App\Livewire\Login;
use App\Livewire\Login\FocalCoordinatorForm;
use Illuminate\Support\Facades\Route;

Route::get('/', Login::class)->name('login');

Route::get('/logoutiftroubled', function () {
    Auth::logout();
    session()->invalidate();
    session()->flush();
    session()->regenerateToken();
});

// -------------------------------------

Route::middleware('auth')->group(function () {

    Route::get('/focal/dashboard', Dashboard::class)->name('focal.dashboard');
    Route::get('/focal/implementations', Implementations::class)->name('focal.implementations');
    Route::get('/focal/user-management', UserManagement::class)->name('focal.user-management');
    Route::get('/focal/activity-logs', ActivityLogs::class)->name('focal.activity-logs');

    Route::get('/coordinator/assignments', Assignments::class)->name('coordinator.assignments');
    Route::get('/coordinator/submissions/{batchId?}', Submissions::class)->name('coordinator.submissions');
    Route::get('/coordinator/forms', Forms::class)->name('coordinator.forms');

    Route::get('/focal/settings', Settings::class)->name('focal.settings');
    // Route::get('/focal/settings', Settings::class)->name('coordinator.settings');

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
