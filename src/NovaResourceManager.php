<?php

namespace Zareismail\NovaResourceManager;

use Illuminate\Suppoer\Srt;
use Laravel\Nova\Nova as LaravelNova;
use Laravel\Nova\Tools\ResourceManager; 

class NovaResourceManager extends ResourceManager
{ 
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        LaravelNova::script('zareismail-nova-resource-manager', __DIR__.'/../dist/js/tool.js');
        LaravelNova::style('zareismail-nova-resource-manager', __DIR__.'/../dist/css/tool.css');
    }

    /**
     * Build the view that renders the navigation links for the tool.
     *
     * @return \Illuminate\View\View
     */
    public function renderNavigation()
    { 
        $resourceName = Nova\NovaResource::uriKey();
        $groupName = Nova\NovaResourceGroup::uriKey();
        $active = $this->guessActiveGroup();

        return "<nova-resource-manager 
            resource-name='{$resourceName}' 
            group-name='{$groupName}' 
            active='{$active}'
        ></nova-resource-manager>";
    } 

    public function guessActiveGroup()
    {
        if($resource = $this->getResoruce()) {
            return optional($resource->group)->label;
        } 
    }

    public function getResoruce()
    { 
        return with(request()->segments()[2] ?? null, function($resourceName) {
            if(! is_null($resourceName)) {
                return Nova\NovaResource::newModel()->with('group')->whereName($resourceName)->first(); 
            }
        });  
    }
}
