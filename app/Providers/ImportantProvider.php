<?php

namespace App\Providers;

use App\Services\ImportantService;
use Illuminate\Support\ServiceProvider;

class ImportantProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ImportantService::class, function($app){
            return new ImportantService();
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
