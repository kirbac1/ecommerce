<?php

namespace App\Traits;

use App\Customer;

trait BelongsToCustomer
{
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function setCustomerAttribute(Customer $customer)
    {
        $this->customer()->associate($customer);
    }
}