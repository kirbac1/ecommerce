<?php

namespace App\Traits;

trait GivesAuthorization
{
    // Getters to simplify user authorization.
    public function getIsSuperAdminAttribute() { return false; }
    public function getIsAdminAttribute() { return false; }
    public function getIsUserAttribute() { return false; }
    public function getIsCustomerAttribute() { return false; }
}