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
        //Services
        $this->app->bind('App\Services\ParkingTicketServiceInterface', 'App\Services\ParkingTicketService');
        $this->app->bind('App\Services\NotificationServiceInterface', 'App\Services\NotificationService');

        //Respositories
        $this->app->bind('App\Repositories\UserRepositoryInterface', 'App\Repositories\UserRepository');
        $this->app->bind('App\Repositories\ParkingTicketRepositoryInterface', 'App\Repositories\ParkingTicketRepository');
        $this->app->bind('App\Repositories\UserParkingTicketRepositoryInterface', 'App\Repositories\UserParkingTicketRepository');
        $this->app->bind('App\Repositories\ParkingVenueRepositoryInterface', 'App\Repositories\ParkingVenueRepository');

    }
}
