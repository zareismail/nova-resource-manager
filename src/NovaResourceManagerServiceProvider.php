<?php

namespace Zareismail\NovaResourceManager;

use Illuminate\Contracts\Support\DeferrableProvider;  
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Tools\ResourceManager;
use Laravel\Nova\Nova as LaravelNova; 
use Laravel\Nova\Events\ServingNova;

class NovaResourceManagerServiceProvider extends ServiceProvider implements DeferrableProvider
{  
    /**
     * Register any nova services.
     *
     * @return void
     */
    public function register()
    { 
        $this->registerTools(); 
        $this->registerPolicies(); 
        $this->registerResources(); 
        $this->removeDefaultResourceManager();   
    }

    /**
     * Register any nova tools.
     *
     * @return void
     */
    public function registerTools()
    { 
        LaravelNova::tools([
            new NovaResourceManager
        ]);
    }

    /**
     * Register the any pacakge policies.
     *
     * @return void
     */
    public function registerPolicies()
    { 
        Gate::policy(Models\NovaResource::class, Policies\NovaResource::class);
        Gate::policy(Models\NovaResourceGroup::class, Policies\NovaResourceGroup::class);
    }

    /**
     * Register any nova resources.
     *
     * @return void
     */
    public function registerResources()
    { 
        LaravelNova::resources(config('nova-resource-manager.resources', [ 
            Nova\NovaResource::class,
            Nova\NovaResourceGroup::class,
        ]));
    }

    /**
     * Remove the Nova default ResourceManager.
     * 
     * @return void
     */
    public function removeDefaultResourceManager()
    {
        LaravelNova::$tools = array_filter(LaravelNova::registeredTools(), function($tool) {
            return get_class($tool) !== ResourceManager::class;
        });
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
            ServingNova::class, 
        ];
    }
}
