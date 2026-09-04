<?php

namespace App\Traits;

use App\Category;

trait BelongsToCategory
{
    /**
     * Returns the category of the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function setCategoryAttribute(Category $category)
    {
        $this->category()->associate($category);
        $this->save();
    }
}