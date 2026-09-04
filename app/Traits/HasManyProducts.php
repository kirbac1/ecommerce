<?php

namespace App\Traits;

use App\Product;

trait HasManyProducts
{
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function addProduct(Product $product, $price=null, $quantity=1, $taxPercent=null)
    {
        if (!$price) { $price = $product->prices->basePrice; }
        $this->products()->save($product, [
            'basePrice' => $price,
            'quantity'  => $quantity,
            'taxPercent' => $taxPercent,
        ]);
        return $this;
    }

    public function deleteProduct(Product $product)
    {
        $this->products()->detach($product);
        return $this;
    }
}