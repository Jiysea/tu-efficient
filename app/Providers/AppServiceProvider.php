<?php

namespace App\Providers;

use App\Models\Archive;
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
        Gate::define('edit-implementation-focal', function (User $user, Implementation $implementation) {
            if ($implementation)
                return $user->id === $implementation->users_id;
            else
                return false;
        });

        Gate::define('delete-implementation-focal', function (User $user, Implementation $implementation) {
            if ($implementation)
                return $user->id === $implementation->users_id;
            else
                return false;
        });


        Gate::define('delete-batch-focal', function (User $user, Batch $batch) {
            $batch = Batch::join('implementations', 'implementations.id', '=', 'batches.implementations_id')
                ->where('batches.implementations_id', $batch->implementations_id)
                ->where('implementations.users_id', $user->id)
                ->first();

            if (isset($batch)) {
                return true;
            } else {
                return false;
            }
        });

        Gate::define('delete-beneficiary-focal', function (User $user, Beneficiary $beneficiary) {
            $beneficiary = Implementation::join('batches', 'batches.implementations_id', '=', 'implementations.id')
                ->join('beneficiaries', 'beneficiaries.batches_id', '=', 'batches.id')
                ->where('beneficiaries.id', $beneficiary->id)
                ->where('implementations.users_id', $user->id)
                ->first();

            if (isset($beneficiary)) {
                return true;
            } else {
                return false;
            }

        });

        Gate::define('delete-coordinator-focal', function (User $user, User $coordinator) {
            if ($coordinator->regional_office === $user->regional_office && $coordinator->field_office === $user->field_office && $user->user_type === 'focal') {
                return true;
            } else {
                return false;
            }

        });

        Gate::define('approval-status-focal', function (User $user, Batch $batch) {
            $implementation = Implementation::find($batch->implementations_id);
            return $user->id === $implementation->users_id;
        });

        # ------------------------------------------------------------------------------------------

        Gate::define('check-coordinator', function (User $user, Batch $batch) {
            $assignment = Assignment::join('batches', 'assignments.batches_id', '=', 'batches.id')
                ->where('batches.id', $batch->id)
                ->where('assignments.users_id', $user->id)
                ->first();

            if (isset($assignment)) {
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

        Gate::define('restore-beneficiary-focal', function (User $user, Archive $archive) {

            if (!$archive) {
                return false;
            }

            $batchId = $archive->data['batches_id'];
            $users_id = Implementation::find(Batch::find($batchId)->implementations_id)->users_id;

            if ($user->id === $users_id) {
                return true;
            } else {
                return false;
            }
        });

        Gate::define('permdelete-beneficiary-focal', function (User $user, Archive $archive) {

            if (!$archive) {
                return false;
            }

            $batchId = $archive->data['batches_id'];
            $users_id = Implementation::find(Batch::find($batchId)->implementations_id)->users_id;

            if ($user->id === $users_id) {
                return true;
            } else {
                return false;
            }
        });
    }
}
