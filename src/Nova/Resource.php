<?php

namespace Zareismail\NovaResourceManager\Nova;
 
use Laravel\Nova\Http\Requests\NovaRequest; 
use Zareismail\NovaContracts\Nova\Resource as BaseResource; 

abstract class Resource extends BaseResource
{  
    /**
     * The per-page options used the resource index.
     *
     * @var array
     */
    public static $perPageOptions = [1000];

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Resource Manager';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'label'; 

    /**
     * The relationships that should be eager loaded when performing an index query.
     *
     * @var array
     */
    public static $with = [];

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [ 
        'label'
    ]; 

    /**
     * Initialize the given index query.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @param  string  $withTrashed
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected static function initializeQuery(NovaRequest $request, $query, $search, $withTrashed)
    {
        return parent::indexQuery($request, $query, $search, $withTrashed)->orderBy('order');
    }

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    { 
        forward_static_call([NovaResource::$model, 'ensureMissingNavigations'], $request); 
        
        return $query; 
    }

    /**
     * Build a Scout search query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Scout\Builder  $query
     * @return \Laravel\Scout\Builder
     */
    public static function scoutQuery(NovaRequest $request, $query)
    {
        return $query;
    }

    /**
     * Build a "detail" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function detailQuery(NovaRequest $request, $query)
    {
        return parent::detailQuery($request, $query);
    }

    /**
     * Build a "relatable" query for the given resource.
     *
     * This query determines which instances of the model may be attached to other resources.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function relatableQuery(NovaRequest $request, $query)
    {  
        return static::authenticateQuery($request, parent::relatableQuery($request, $query));
    } 
}