<?php

namespace Zareismail\NovaResourceManager\Models;
 
use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NovaResource extends Model
{
    use HasFactory, InteractsWithNavigations;  
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'payload' => 'json'
    ]; 

    /**
     * Query the realted NovaResourceGroup.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(NovaResourceGroup::class);
    } 

    /**
     * Query resource of the given resources.
     * 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeResourceIn($query, $resources)
    {
        return $query->whereIn($query->qualifyColumn('resource'), (array) $resources);
    }
}
