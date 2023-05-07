<?php

namespace App\Providers;

use App\Services\PPService;
use Illuminate\Support\ServiceProvider;

class PPProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PPService::class, function($app){
            return new PPService();
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
