<?php

namespace App\Providers;

use App\Models\Gruzootpravitel;
use App\Services\OrderService;
use Illuminate\Support\ServiceProvider;
use App\Models\Orders;

class OrderProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(OrderService::class, function($app){
            return new OrderService(new Orders, new Gruzootpravitel);
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
