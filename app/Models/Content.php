<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Content extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = ['title', 'description', 'body', 'type'];

    protected $casts = [
        'created_at' => 'date:d/m/Y H:i:s'
    ];

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    /**
     * Sluggable Config
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }
}
