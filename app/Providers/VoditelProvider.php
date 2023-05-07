<?php

namespace App\Providers;

use App\Services\VoditelService;
use Illuminate\Support\ServiceProvider;

class VoditelProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(VoditelService::class, function($app){
            return new VoditelService();
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
