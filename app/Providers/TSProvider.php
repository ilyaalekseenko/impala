<?php

namespace App\Providers;

use App\Models\TS;
use App\Services\TSService;
use Illuminate\Support\ServiceProvider;

class TSProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TSService::class, function($app){
            return new TSService(new TS);
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
