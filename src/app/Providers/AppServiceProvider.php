<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //

        $this->app->bind('App\Services\ParkingTicketServiceInterface', 'App\Services\ParkingTicketService');
        $this->app->bind('App\Repositories\UserRepositoryInterface', 'App\Repositories\UserRepository');

    }
}
