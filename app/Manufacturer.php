<?php

namespace App;

use App\Models\Model;
use App\Traits\HasManyProducts;

class Manufacturer extends Model
{
    use HasManyProducts;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'visible',
    ];

    /**
     * The attributes that will be hidden.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    public static function search($string, $start=0, $limit=40)
    {
        return Manufacturer::like([
            'name'
        ], "%$string%")->skip($start)->take($limit)->get();
    }
}