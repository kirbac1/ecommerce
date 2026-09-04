<?php

namespace App;

use App\Models\Model;

class PaymentMethod extends Model
{
    public $timestamps = false;

    /**
     * Returns the payments made with this payment method.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Searches for a payment method with given attributes.
     *
     * @param $string
     * @return mixed
     */
    public static function search($string, $start=0, $limit=40)
    {
        return PaymentMethod::like([
            'name'
        ], "%$string%")->skip($start)->take($limit)->get();
    }
}