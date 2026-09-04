<?php

namespace App\Traits;

use App\Customer;

trait HasManyCustomers
{
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * Attaches a customer to this element.
     *
     * @param Customer $customer
     * @return $this
     */
    public function addCustomer(Customer $customer)
    {
        $this->users()->attach($customer);
        return $this;
    }

    /**
     * Detaches a customer from this element.
     *
     * @param Customer $customer
     * @return $this
     */
    public function delCustomer(Customer $customer)
    {
        $this->users()->detach($customer);
        return $this;
    }
}