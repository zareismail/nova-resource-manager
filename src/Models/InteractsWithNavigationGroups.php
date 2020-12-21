<?php

namespace Zareismail\NovaResourceManager\Models;
 
 use Illuminate\Http\Request;
 use Laravel\Nova\Nova;

trait InteractsWithNavigationGroups
{
	/**
	 * Ensure all navigations inserted.
	 * 
	 * @param  \Illuminate\Http\Request $request 
	 * @return static           
	 */
	public static function ensureMissingNavigationGroups(Request $request)
	{
		if(static::hasMissingNavigationGroup($request)) {
			static::loadMissingNavigationGroups($request);
		}

		return new static;
	}

	/**
	 * Insert missed nvaigations.
	 * 
	 * @param  \Illuminate\Http\Request $request 
	 * @return boolean           
	 */
    public static function loadMissingNavigationGroups(Request $request)
    {  
    	static::insert(static::missedNvaigationGroups($request)->values()->map(function($label, $order) {
            return compact('label', 'order');
        }));

    	return new static;
    }

    /**
     * Returns missed nagigations.
     *  
	 * @param  \Illuminate\Http\Request $request 
     * @return \Laravel\Nova\ResourceCollection
     */
    public static function missedNvaigationGroups(Request $request)
    {
    	$groups = static::get()->map->label;

    	return Nova::groups($request)->filter(function($group) use ($groups) {
            return ! $groups->contains($group);
        }); 
    }   

	/**
	 * Determine if there is a missing navigation.
	 * 
	 * @param  \Illuminate\Http\Request $request 
	 * @return boolean           
	 */
	public static function hasMissingNavigationGroup(Request $request)
	{  
		return static::count() !== Nova::groups($request)->count();
	} 
}
