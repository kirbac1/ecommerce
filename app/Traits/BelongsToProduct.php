<?php

namespace App\Traits;

use App\Product;

trait BelongsToProduct
{
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function setProductAttribute(Product $product)
    {
        $this->product()->associate($product);
        return $this;
    }
}