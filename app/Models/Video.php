<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Video extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'code', 'name', 'description', 'video',
        'thumb', 'slug', 'is_processed'
    ];

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }

    /**
     * Sluggable Config
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}
