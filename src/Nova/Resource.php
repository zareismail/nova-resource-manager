<?php

namespace Zareismail\NovaResourceManager\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest; 
use Laravel\Nova\Resource as BaseResource; 

abstract class Resource extends BaseResource
{  
    /**
     * The per-page options used the resource index.
     *
     * @var array
     */
    public static $perPageOptions = [1000];

    /**
     * The number of resources to show per page via relationships.
     *
     * @var int
     */
    public static $perPageViaRelationship = 1000;

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
     * Determine if the resource should be available for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorizeToViewAny(Request $request)
    {
        return true;
    }

    /**
     * Determine if the resource should be available for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function authorizedToViewAny(Request $request)
    {
        return true;
    }

    /**
     * Get the actions available on the entity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            Actions\Refresh::make()->standalone(),
        ];
    }
}
