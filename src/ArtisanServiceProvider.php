<?php

namespace Zareismail\NovaResourceManager;

use Illuminate\Contracts\Support\DeferrableProvider; 
use \Illuminate\Console\Events\ArtisanStarting;
use Illuminate\Support\ServiceProvider; 

class ArtisanServiceProvider extends ServiceProvider implements DeferrableProvider
{  
    /**
     * Register any Artisan services.
     *
     * @return void
     */
    public function register()
    { 
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');   
    } 

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
        ];
    }

    /**
     * Get the events that trigger this service provider to register.
     *
     * @return array
     */
    public function when()
    {
        return [ 
            ArtisanStarting::class,
        ];
    }
}
