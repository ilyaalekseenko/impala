<?php

namespace App\Providers;

use App\Services\PerevozchikService;
use Illuminate\Support\ServiceProvider;

class PerevozchikProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PerevozchikService::class, function($app){
            return new PerevozchikService();
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
