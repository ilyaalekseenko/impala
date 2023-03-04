<?php

namespace App\Providers;

use App\Services\OplataService;
use Illuminate\Support\ServiceProvider;

class OplataProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(OplataService::class, function($app){
            return new OplataService();
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
