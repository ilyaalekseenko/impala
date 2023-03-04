<?php

namespace App\Providers;

use App\Models\DocsTemplate;
use App\Services\DocService;
use Illuminate\Support\ServiceProvider;

class DocProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(DocService::class, function($app){
            return new DocService(new DocsTemplate);
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
