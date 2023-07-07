<?php

namespace App\Providers;

use App\Services\SearchService;
use Illuminate\Support\ServiceProvider;

class SearchProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SearchService::class, function($app){
            return new SearchService();
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
