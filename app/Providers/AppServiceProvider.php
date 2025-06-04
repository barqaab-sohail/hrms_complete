<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Relations\Relation;
use Livewire;
use App\Models\Submission\Security;
use App\Observers\SecurityObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {}



    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Security::observe(SecurityObserver::class);

        // Livewire::setUpdateRoute(function ($handle) {
        //     return Route::post('/hrms11/livewire/update', $handle);
        // });

    }
}
