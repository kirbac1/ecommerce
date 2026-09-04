<?php

namespace App;

use App\Models\Model;
use App\Traits\BelongsToOrder;
use App\Traits\BelongsToCustomer;
use App\Traits\BelongsToPaymentMethod;

class Payment extends Model
{
    use BelongsToCustomer, BelongsToPaymentMethod, BelongsToOrder;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id', 'payment_method_id', 'order_id', 'transaction_id', 'cashier_id',
    ];

    /**
     * The attributes that will be hidden.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at',
    ];

    /**
     * Searches for a payment with given attributes.
     *
     * @param $string
     * @return mixed
     */
    public static function search($string, $start=0, $limit=40)
    {
        return Payment::like([
            'transaction_id'
        ], "%$string%")->skip($start)->take($limit)->get();
    }
}