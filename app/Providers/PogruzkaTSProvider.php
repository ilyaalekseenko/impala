<?php

namespace App\Providers;

use App\Models\PogruzkaTS;
use App\Services\PogruzkaTSService;
use Illuminate\Support\ServiceProvider;

class PogruzkaTSProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PogruzkaTSService::class, function($app){
            return new PogruzkaTSService(new PogruzkaTS);
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
