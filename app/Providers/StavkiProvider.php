<?php

namespace App\Providers;

use App\Services\StavkiService;
use Illuminate\Support\ServiceProvider;

class StavkiProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(StavkiService::class, function($app){
            return new StavkiService();
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
