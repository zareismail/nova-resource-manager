<?php

namespace Zareismail\NovaResourceManager\Nova;

use Illuminate\Http\Request; 
use Laravel\Nova\Fields\{ID, Text, Number, Boolean, BelongsTo};
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;

class NovaResource extends Resource
{    
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Zareismail\NovaResourceManager\Models\NovaResource::class;

    /**
     * The relationships that should be eager loaded when performing an index query.
     *
     * @var array
     */
    public static $with = ['group']; 

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(),

            BelongsTo::make(__('Group'), 'group', NovaResourceGroup::class)
                ->showCreateRelationButton()
                ->sortable()
                ->inverse('resources'),

            Text::make(__('Label'), 'label')
                ->sortable(),

            Number::make(__('Order'), 'order') 
                ->sortable(), 

            Text::make(__('Name'), 'name')
                ->exceptOnForms()
                ->sortable(), 

            Boolean::make(__('Visible'), 'visible')
                ->default(true)
                ->sortable()
                ->canSee(function($request) {
                    if($request->isUpdateOrUpdateAttachedRequest()) {  
                        return $request->findModelOrFail()->resource !== static::class;
                    } 

                    return true;
                }),
        ];
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
        return parent::indexQuery($request, $query)->tap(function($query) use ($request) {
            $query->resourceIn(Nova::resourcesForNavigation($request));
        });
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
    public static function relatableQuerys(NovaRequest $request, $query)
    {  
        return static::authenticateQuery($request, parent::relatableQuery($request, $query));
    } 
    /**
     * Determine if the current user can delete the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToDelete(Request $request)
    {
        return false;
    }

    /**
     * Determine if the current user can force delete the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function authorizedToForceDelete(Request $request)
    {
        return false;
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
}