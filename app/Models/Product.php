<?php

namespace App\Models;

use App\Enums\ProductStatusEnum;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;


    public  function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('thumb')
            ->width(100);
        $this
            ->addMediaConversion('small')
            ->width(480);
        $this
            ->addMediaConversion('large')
            ->width(1200);
    }

    public function scopeForVendor(Builder $query): Builder
    {
        return $query->where("created_by", auth()->user()->id);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where("status", ProductStatusEnum::Published);
    }
    /**
     * Get the User that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "created_by");
    }
    /**
     * Get the department that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
    /**
     * Get the category that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    /**
     * Get all of the variationTypes for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variationTypes(): HasMany
    {
        return $this->hasMany(VariationType::class);
    }

    /**
     * Get all of the variations for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }
}
