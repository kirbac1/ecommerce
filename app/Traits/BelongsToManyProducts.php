<?php

namespace App\Traits;

use App\Product;
use Carbon\Carbon;

trait BelongsToManyProducts
{
    public function products()
    {
        return $this->belongsToMany(Product::class, $this->pivot_table);
    }

    public function addProduct($product, $price=null, $quantity=1, $taxPercent=null)
    {
        $priceEdited = false;
        if (!$price) {
            $price = $product->basePrice;
        } else {
            if ($product->basePrice <> $price) {
                $priceEdited = true;
            }
        }
        $this->products()->attach($product, [
            'priceEach' => $price,
            'quantity'  => $quantity,
            'isPriceEdited' => $priceEdited,
            'taxPercent' => $product->taxPercent,
            'created_at' => Carbon::now(),
        ]);
        return $this;
    }

    public function deleteProduct(Product $product)
    {
        $this->products()->detach($product);
        return $this;
    }
}