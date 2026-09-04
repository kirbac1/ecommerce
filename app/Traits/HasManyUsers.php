<?php

namespace App\Traits;

use App\Customer;

trait HasManyCustomers
{
    public function users()
    {
        return $this->hasMany(Customer::class);
    }
}