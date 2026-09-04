<?php

namespace App;

use App\Models\Model;
use App\Traits\BelongsToUser;
use App\Traits\BelongsToCustomer;
use App\Traits\BelongsToManyProducts;
use App\Traits\BelongsToPaymentMethod;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proforma extends Model
{
    use BelongsToCustomer, BelongsToUser, BelongsToManyProducts, BelongsToPaymentMethod;
    use SoftDeletes;

    protected $fillable = ['customer_id', 'user_id', 'entityType', 'name', 'surname', 'email1', 'email2', 'website', 'phone', 'mobile', 'vatid', 'taxid', 'street1', 'street2', 'city', 'state', 'zipcode', 'country', 'notes', 'discount'];
    private $pivot_table = 'proforma_product';
    protected $accessors = ['total', 'taxTotal', 'totalWithoutTaxes', 'products'];

    /**
     * The attributes that will be hidden.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at'
    ];

    /**
     * Accessor for getting the total amount of the proforma.
     *
     * @return float
     */
    public function getTotalAttribute()
    {
        $productsTotalAmount = 0.00;
        foreach($this->products as $product)
        {
            $priceEach = $product->pivot->priceEach;
            $taxPercent = $product->pivot->taxPercent;
            $quantity = $product->pivot->quantity;
            $productsTotalAmount += $priceEach * $quantity * (100 + $taxPercent) / 100;
        }
        return $this->format_number($productsTotalAmount,2);
    }

    /**
     * Accessor for getting the total taxes charged to the proforma.
     *
     * @return mixed
     */
    public function getTaxTotalAttribute()
    {
        $totalTaxes = 0.00;
        foreach($this->products as $product)
        {
            $priceEach = $product->pivot->priceEach;
            $taxPercent = $product->pivot->taxPercent;
            $quantity = $product->pivot->quantity;
            $totalTaxes += $priceEach * $quantity * $taxPercent / 100;
        }
        return $this->format_number($totalTaxes, 2);
    }

    /**
     * Accessor for getting the total amount (without taxes) for the proforma.
     *
     * @return string
     */
    public function getTotalWithoutTaxesAttribute()
    {
        $productsTotalNetAmount = 0.00;
        foreach($this->products as $product)
        {
            $priceEach = $product->pivot->priceEach;
            $taxPercent = $product->pivot->taxPercent;
            $quantity = $product->pivot->quantity;
            $productsTotalNetAmount += $priceEach * $quantity;
        }
        return $this->format_number($productsTotalNetAmount, 2);
    }

    /**
     * Retrieves the products inside this proforma.
     *
     * @return $this
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'proforma_product')->withPivot(['quantity', 'priceEach', 'taxPercent', 'isPriceEdited']);
    }

    /**
     * Removes all the products from the current proforma.
     *
     * @return Proforma $this
     */
    public function removeAllProducts()
    {
        $this->products()->detach();
        return $this;
    }

    /**
     * Return the associated order (if any).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function order()
    {
        return $this->hasOne(Order::class);
    }

    /**
     * Float formatter helper
     *
     * @param $number
     * @param $decimals
     * @return string
     */
    protected function format_number($number, $decimals=4)
    {
        return number_format(round($number, $decimals), $decimals);
    }

    /**
     * Searches proformas.
     *
     * @param $string
     * @return mixed
     */
    public static function search($string)
    {
        return Proforma::with(['customer', 'products'])->where('id', str_replace('#', '', $string))->first();
    }
}