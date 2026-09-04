<?php

namespace App\Traits;

use App\PaymentMethod;

trait BelongsToPaymentMethod
{
    public function method()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function setMethodAttribute(PaymentMethod $method)
    {
        $this->method()->associate($method);
    }
}