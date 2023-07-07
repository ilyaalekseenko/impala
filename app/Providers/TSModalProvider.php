<?php

namespace App\Providers;

use App\Services\TSModalService;
use Illuminate\Support\ServiceProvider;

class TSModalProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TSModalService::class, function($app){
            return new TSModalService();
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
