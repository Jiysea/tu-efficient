<?php

use App\Http\Controllers\BarangayController;
use App\Http\Controllers\ImageController;
use App\Livewire\Barangay\ListingPage;
use App\Livewire\Coordinator\Assignments;
use App\Livewire\Coordinator\Submissions;
use App\Livewire\Coordinator\Forms;
use App\Livewire\Focal\ActivityLogs;
use App\Livewire\Focal\Archives;
use App\Livewire\Focal\Dashboard;
use App\Livewire\Focal\Implementations;
use App\Livewire\Focal\Settings;
use App\Livewire\Focal\UserManagement;
use App\Livewire\Login;
use App\Livewire\Login\FocalCoordinatorForm;
use Illuminate\Support\Facades\Route;

# Landing page
Route::get('/', Login::class)->name('login');

# -------------------------------------

Route::middleware('auth')->group(function () {

    # For Focal pages
    Route::get('/focal/dashboard', Dashboard::class)->name('focal.dashboard');
    Route::get('/focal/implementations', Implementations::class)->name('focal.implementations');
    Route::get('/focal/user-management', UserManagement::class)->name('focal.user-management');
    Route::get('/focal/archives', Archives::class)->name('focal.archives');
    Route::get('/focal/activity-logs', ActivityLogs::class)->name('focal.activity-logs');

    Route::get('/credentials/{filename}', [ImageController::class, 'showImage'])
        ->where('filename', '.*')
        ->name('credentials.show');

    # For Coordinator pages
    Route::get('/coordinator/assignments', Assignments::class)->name('coordinator.assignments');
    Route::get('/coordinator/submissions/{batchId?}', Submissions::class)->name('coordinator.submissions');
    Route::get('/coordinator/forms', Forms::class)->name('coordinator.forms');

    Route::get('/focal/settings', Settings::class)->name('focal.settings');
    // Route::get('/focal/settings', Settings::class)->name('coordinator.settings');

    # For Printing
    Route::get('/print-summary', function () {
        # Get information from the session and provide default values
        $information = session('print-summary-information');
        return view('pages/print-summary', $information);
    })->name('focal.print-summary');
});

// For barangay officials (access code user)
Route::get('/barangay/listing', ListingPage::class)->name('barangay.index');

Route::get('/credentials/{filename}', [ImageController::class, 'showImage'])
    ->where('filename', '.*')
    ->name('credentials.baranagay.show');

// --------------------------------------

// Route::get('/logoutiftroubled', function () {
//     Auth::logout();
//     session()->invalidate();
//     session()->flush();
//     session()->regenerateToken();
// });

// Route::get('/focal/verify', FocalSMSVerificationController::class)->name('focal.verify');
// Route::get('/coordinator/verify', CoordinatorSMSVerificationController::class)->name('coordinator.verify');


// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__ . '/auth.php';
