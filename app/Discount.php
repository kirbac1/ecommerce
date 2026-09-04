<?php

namespace App;

use App\Models\Model;
use App\Traits\BelongsToProduct;

class Discount extends Model
{
    use BelongsToProduct;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'product_id', 'valuePercent', 'valueAmount', 'type',
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
     * Searches discounts with given attributes.
     *
     * @param $string
     */
    public static function search($string, $start=0, $limit=40)
    {
        return Discount::like([
            'name'
        ], "%$string%")->skip($start)->take($limit)->get();
    }
}