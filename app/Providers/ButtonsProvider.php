<?php

namespace App\Providers;

use App\Models\Orders;
use App\Services\ButtonsService;
use Illuminate\Support\ServiceProvider;

class ButtonsProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ButtonsService::class, function($app){
            return new ButtonsService(new Orders());
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
