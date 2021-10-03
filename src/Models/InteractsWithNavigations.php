<?php

namespace Zareismail\NovaResourceManager\Models;
 
 use Illuminate\Http\Request;
 use Laravel\Nova\Nova;

trait InteractsWithNavigations
{
	/**
	 * Ensure all navigations inserted.
	 * 
	 * @param  \Illuminate\Http\Request $request 
	 * @return static           
	 */
	public static function ensureMissingNavigations(Request $request)
	{
		if(static::hasMissingNavigation($request)) {
			static::loadMissingNavigations($request);
		}

		return new static;
	}

	/**
	 * Insert missed nvaigations.
	 * 
	 * @param  \Illuminate\Http\Request $request 
	 * @return boolean           
	 */
    public static function loadMissingNavigations(Request $request)
    {
    	$resources = static::missedNvaigations($request)->map(function($resource, $key) {
    		return array_merge(static::resourceNavigationInformation($resource), [
                'order' => intval($key)
            ]);
    	});

    	static::insert($resources->values()->all());

    	return new static;
    }

    /**
     * Returns missed nagigations.
     *  
	 * @param  \Illuminate\Http\Request $request 
     * @return \Laravel\Nova\ResourceCollection
     */
    public static function missedNvaigations(Request $request)
    {
    	$resources = static::get()->map->resource;

    	return Nova::authorizedResources($request)
                ->availableForNavigation($request)->filter(function($resource) use ($resources) {
                    return ! $resources->contains($resource);
                }); 
    }  

    /**
     * Returns nvigations required informations for database.
     * 
     * @param  \Laravel\Nova\resource $resource
     * @return array
     */
    public static function resourceNavigationInformation($resource)
    {
        return [
            'resource'  => $resource,
            'label'     => $resource::label(),
            'name'      => $resource::uriKey(),
            'group_id'  => NovaResourceGroup::firstOrCreate(['label' => $resource::group()], [ 
                'order' => NovaResourceGroup::count(), 
            ])->id,  
        ];
    }

	/**
	 * Determine if there is a missing navigation.
	 * 
	 * @param  \Illuminate\Http\Request $request 
	 * @return boolean           
	 */
	public static function hasMissingNavigation(Request $request)
	{  
		return static::count() !== Nova::authorizedResources($request)->count();
	} 
}
