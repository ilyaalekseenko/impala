<?php

namespace App\Providers;

use App\Models\UnreadHeader;
use App\Services\UnreadHeaderService;
use Illuminate\Support\ServiceProvider;

class UnreadHeaderProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UnreadHeaderService::class, function($app){
            return new UnreadHeaderService(new UnreadHeader);
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
