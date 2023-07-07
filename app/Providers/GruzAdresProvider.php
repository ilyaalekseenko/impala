<?php

namespace App\Providers;

use App\Models\Gruzootpravitel;
use App\Models\GruzootpravitelAdresa;
use App\Services\GruzootpravitelAdresService;
use Illuminate\Support\ServiceProvider;

class GruzAdresProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(GruzootpravitelAdresService::class, function($app){
            return new GruzootpravitelAdresService(new Gruzootpravitel, new GruzootpravitelAdresa());
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
