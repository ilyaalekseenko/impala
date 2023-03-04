<?php

namespace App\Providers;

use App\Services\GruzootpravitelService;
use Illuminate\Support\ServiceProvider;

class GruzootpravitelProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(GruzootpravitelService::class, function($app){
            return new GruzootpravitelService();
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
