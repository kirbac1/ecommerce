<?php

namespace App;

use App\Models\Model;
use App\Traits\BelongsToUser;
use App\Traits\BelongsToCustomer;
use App\Traits\BelongsToManyProducts;
use App\Traits\BelongsToPaymentMethod;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Returned extends Model
{
    use BelongsToCustomer, BelongsToUser, BelongsToManyProducts, BelongsToPaymentMethod;
    use SoftDeletes;

    protected $fillable = ['order_id', 'user_id', 'entityType', 'company', 'name', 'surname', 'email1', 'email2', 'website', 'phone', 'mobile', 'vatid', 'taxid', 'street1', 'street2', 'city', 'state', 'zipcode', 'country', 'notes', 'discount'];
    private $pivot_table = 'return_product';
    protected $accessors = ['total', 'taxTotal', 'totalWithoutTaxes', 'products',];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'returns';

    /**
     * The attributes that will be hidden.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at'
    ];

    /**
     * Overridden standard addProduct method.
     *
     * @param $product
     * @param int $quantity
     * @return $this
     */
    public function addProduct($product, $quantity=1)
    {
        $this->products()->attach($product, [
            'quantity'  => $quantity,
            'created_at' => Carbon::now(),
        ]);
        return $this;
    }

    /**
     * Accessor for getting the total amount of the return.
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
     * Accessor for getting the total taxes charged to the return.
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
     * Accessor for getting the total amount (without taxes) for the return.
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
     * Retrieves the products inside this return.
     *
     * @return $this
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'return_product', 'return_id', 'product_id')->withTrashed()->withPivot(['quantity', 'priceEach', 'taxPercent']);
    }

    /**
     * Removes all the products from the current return.
     *
     * @return Returned $this
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
        return $this->hasOne(Order::class, 'id', 'order_id')->withTrashed();
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
     * Searches for a return with given attributes.
     *
     * @param $string
     * @return mixed
     */
    public static function search($string, $start=0, $limit=20, $order_id=null)
    {
        if ($order_id) {
            return Returned::like([
                'rma'
            ], "%$string%")->where('order_id', $order_id)->skip($start)->take($limit)->get();
        } else {
            return Returned::like([
                'rma'
            ], "%$string%")->skip($start)->take($limit)->get();
        }
    }
}