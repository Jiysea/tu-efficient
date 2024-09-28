<?php

namespace App\Providers;

use App\Models\Batch;
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
    }
}
