<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\MySqlConnection; // Or any other concrete implementation you use

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Bind the ConnectionInterface to a concrete implementation
        $this->app->bind(
            \Illuminate\Database\ConnectionInterface::class,
            MySqlConnection::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
