<?php

namespace App\Providers;

use App\Models\Assignment;
use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Code;
use App\Models\Implementation;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('delete-implementation', function (User $user, Implementation $implementation) {
            return $user->id === $implementation->users_id;
        });

        Gate::define('delete-batch', function (User $user, Implementation $implementation, Batch $batch) {
            if ($batch->implementations_id === $implementation->id) {
                return $user->id === $implementation->users_id;
            } else {
                return false;
            }
        });

        Gate::define('delete-beneficiary-focal', function (User $user) {
            $implementation = Batch::join('implementations', 'implementations.id', '=', 'batches.implementations_id')
                ->select('implementations.users_id')
                ->first();

            if ($implementation->users_id === $user->id) {
                return true;
            } else {
                return false;
            }
        });

        Gate::define('approve-submission-coordinator', function (User $user, Batch $batch) {
            $assignments = Assignment::join('batches', 'assignments.batches_id', '=', 'batches.id')
                ->where('batches.id', $batch->id)
                ->where('assignments.users_id', $user->id)
                ->first();

            if (isset($assignments)) {
                return true;
            } else {
                return false;
            }
        });

        Gate::define('delete-beneficiary-coordinator', function (User $user, Beneficiary $beneficiary) {
            $assignments = Assignment::join('batches', 'assignments.batches_id', '=', 'batches.id')
                ->join('beneficiaries', 'beneficiaries.batches_id', '=', 'batches.id')
                ->where('beneficiaries.id', $beneficiary->id)
                ->where('assignments.users_id', $user->id)
                ->first();

            if (isset($assignments)) {
                return true;
            } else {
                return false;
            }
        });
    }
}
