<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    /**
     * Get all of the departments for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function departments(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the parent that owns the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
}
