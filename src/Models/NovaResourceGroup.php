<?php

namespace Zareismail\NovaResourceManager\Models;
 
use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Zareismail\NovaResourceManager\Nova\NovaResource as Resource;

class NovaResourceGroup extends Model
{
    use HasFactory;  
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]|bool
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [ 
    ];  

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function($model) {
            if(! $model->visible && $model->resources()->whereResource(Resource::class)->count()) {
                $model->forceFill(['visible' => true]);
            }
        });
    }

    /**
     * Query the realted NovaResource.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function resources()
    {
        return $this->hasMany(NovaResource::class, 'group_id');
    }
}
