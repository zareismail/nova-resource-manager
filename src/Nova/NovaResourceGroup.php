<?php

namespace Zareismail\NovaResourceManager\Nova;

use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Http\Requests\NovaRequest; 
use Laravel\Nova\Fields\{ID, Text, Number, Boolean}; 
use Laravel\Nova\Nova; 

class NovaResourceGroup extends Resource
{  
    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'label';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Zareismail\NovaResourceManager\Models\NovaResourceGroup::class;

    /**
     * The relationships that should be eager loaded when performing an index query.
     *
     * @var array
     */
    public static $with = []; 

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

            Text::make(__('Label'), 'label')
                ->sortable(),  

            Number::make(__('Order'), 'order') 
                ->sortable(), 

            Boolean::make(__('Visible'), 'visible')
                ->default(true)
                ->sortable()
                ->canSee(function($request) {
                    if($request->isUpdateOrUpdateAttachedRequest()) {
                        $resource = $request->findModelOrFail();

                        return $resource->resources()->whereResource(NovaResource::class)->count() == 0;
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
            $query->whereHas('resources', function($query) use ($request) {
                return $query->resourceIn(Nova::availableResources($request));
            }); 
        });
    }
}