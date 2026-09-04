<?php

namespace App\Traits;

use App\Warehouse;

trait BelongsToWarehouse
{
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function setWarehouseAttribute(Warehouse $warehouse)
    {
        $this->warehouse()->associate($warehouse);
        return $this;
    }
}