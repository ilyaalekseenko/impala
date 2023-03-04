<?php

namespace App\Providers;

use App\Models\Orders;
use App\Services\LogistService;
use Illuminate\Support\ServiceProvider;

class LogistProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(LogistService::class, function($app){
            return new LogistService(new Orders());
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
