<?php

namespace App\Traits;

use App\Order;

trait BelongsToOrder
{
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function setOrderAttribute(Order $order)
    {
        $this->order()->associate($order);
    }
}