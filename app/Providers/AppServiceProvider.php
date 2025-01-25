<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
    }

    

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        \Livewire::setUpdateRoute(function ($handle) {
            return Route::post('/hrms11/public/livewire/update', $handle);
        });
        
        // \Livewire::setScriptRoute(function ($handle) {
        //     return config('app.debug') 
        //         ? Route::get('/hrms11/public/livewire/livewire.js', $handle)
        //         : Route::get('/hrms11/public/livewire/livewire.min.js', $handle);
        // });
    }
}
