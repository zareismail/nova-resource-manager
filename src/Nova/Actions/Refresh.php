<?php

namespace Zareismail\NovaResourceManager\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Zareismail\NovaResourceManager\Models\{NovaResource, NovaResourceGroup};

class Refresh extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        NovaResource::get()->each->forceDelete();
        NovaResourceGroup::get()->each->forceDelete(); 
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
