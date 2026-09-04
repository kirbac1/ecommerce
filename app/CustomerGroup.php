<?php

namespace App;

use App\Models\Model;
use App\Traits\HasManyCustomers;

class CustomerGroup extends Model
{
    use HasManyCustomers;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'discountPercent', 'enabled',
    ];

    /**
     * The attributes that will be hidden.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at'
    ];

    /**
     * Return the customers associated to this customer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * Searches for customer groups with given attributes.
     *
     * @param $string
     * @return mixed
     */
    public static function search($string, $start=0, $limit=40)
    {
        return CustomerGroup::like([
            'name'
        ], "%$string%")->skip($start)->take($limit)->get();
    }
}