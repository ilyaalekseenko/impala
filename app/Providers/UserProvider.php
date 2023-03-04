<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class UserProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserService::class, function($app){
            return new UserService(new Role, new User);
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
