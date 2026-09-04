<?php

namespace App;

use App\Models\Model;
use App\Traits\BelongsToProduct;

class ProductOption extends Model
{
    use BelongsToProduct;

    /**
     * The attributes that will be hidden.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at',
    ];
}