<?php

namespace App\Providers;

use App\Models\Gruzootpravitel;
use App\Models\GruzootpravitelAdresa;
use App\Models\GruzootpravitelBank;
use App\Models\GruzootpravitelContact;
use App\Models\GruzootpravitelFile;
use App\Services\GruzootpravitelAdresService;
use App\Services\GruzootpravitelService;
use Illuminate\Support\ServiceProvider;

class GruzootpravitelProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(GruzootpravitelService::class, function($app){
            return new GruzootpravitelService(new Gruzootpravitel, new GruzootpravitelContact, new GruzootpravitelAdresa, new GruzootpravitelBank, new GruzootpravitelFile);
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
