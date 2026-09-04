<?php

namespace App;

use App\Models\Model;
use App\Traits\BelongsToUser;
use App\Traits\BelongsToCustomer;
use App\Traits\BelongsToManyProducts;
use App\Traits\BelongsToPaymentMethod;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use BelongsToCustomer, BelongsToUser, BelongsToManyProducts, BelongsToPaymentMethod;
    use SoftDeletes;

    protected $fillable = ['customer_id', 'user_id', 'entityType', 'name', 'surname', 'email1', 'email2', 'website',
        'phone', 'mobile', 'vatid', 'taxid', 'street1', 'street2', 'city', 'state', 'zipcode', 'country', 'notes', 'discount'];
    private $pivot_table = 'order_product';
    protected $accessors = ['total', 'taxTotal', 'totalWithoutTaxes', 'products', 'completed'];

    /**
     * The attributes that will be hidden.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at'
    ];

    /**
     * Accessor for getting the total amount of the order.
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
        return $this->format_number($productsTotalAmount);
    }

    /**
     * Accessor for getting the total taxes charged to the order.
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
        return $this->format_number($totalTaxes, 4);
    }

    /**
     * Accessor for getting the total amount (without taxes) for the order.
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
        return $this->format_number($productsTotalNetAmount, 4);
    }

    /**
     * Retrieves the associated proforma (if any).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function proforma()
    {
        return $this->belongsTo(Proforma::class);
    }

    /**
     * Retrieves the associated returns (if any).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function returns()
    {
        return $this->hasMany(Returned::class);
    }

    /**
     * Retrieves the associated invoice.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function invoice()
    {
        return $this->hasOne(Invoice::class)->whereRaw('invoices.due_at is not null');
    }

    /**
     * Retrieves the associated receipt.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function receipt()
    {
        return $this->hasOne(Invoice::class)->where('invoices.due_at', null)->where('invoices.paid', true);
    }

    /**
     * Retrieves the products inside this order.
     *
     * @return $this
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot(['quantity', 'priceEach', 'taxPercent', 'isPriceEdited']);
    }

    /**
     * Removes all the products from the current order.
     *
     * @return Order $this
     */
    public function removeAllProducts()
    {
        $this->products()->detach();
        return $this;
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
     * Accessor to check if an order is completed.
     *
     * @return bool
     */
    public function getCompletedAttribute()
    {
        return ($this->invoice || $this->receipt);
    }

    /**
     * Searches orders.
     *
     * @param $string
     * @return mixed
     */
    public static function search($string)
    {
        return Proforma::with(['customer', 'products'])->where('id', str_replace('#', '', $string))->first();
    }
}
