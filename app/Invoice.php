<?php

namespace App;

use App\Models\Model;
use App\Traits\BelongsToCustomer;
use App\Traits\BelongsToManyProducts;
use App\Traits\BelongsToPaymentMethod;
use App\Traits\BelongsToUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use BelongsToCustomer, BelongsToUser, BelongsToManyProducts, BelongsToPaymentMethod;
    use SoftDeletes;

    protected $fillable = ['customer_id', 'user_id', 'entityType', 'name', 'surname', 'email1', 'email2', 'website',
        'phone', 'mobile', 'vatid', 'taxid', 'street1', 'street2', 'city', 'state', 'zipcode', 'country', 'notes',
        'discount', 'taxed_total', 'untaxed_total', 'taxes_total', 'due_at', 'paid'];
    private $pivot_table = 'invoice_product';
    protected $accessors = ['total', 'taxTotal', 'totalWithoutTaxes', 'products', 'referenceNumber','dueDate'];

    /**
     * The attributes that will be hidden.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at'
    ];

    /** Get due date attributte as timestamp
    *
    */
     public function getDueDateAttribute()

    {
       return  Carbon::parse($this->due_at)->format('d-m-Y');     
    }

    /**
     * ReferenceNumber accessor.    
     * Reference number calculation using the algorithm provided by Pankade liit 7-3-1
     * @return string
     */
    public function getReferenceNumberAttribute()

    {
        $ixOrder = $this->id;
        $customer_id = $this->customer_id;
        $scustomer_id = (string)$customer_id;
               
        $rsMultiplier = array(
            7,
            3,
            1
        );
        $ixCurrentMultiplier = 0;
        $sixOrder = (string)$ixOrder . $scustomer_id ;

        for ($i = strlen($sixOrder) - 1; $i >= 0; $i--) {
            $rsProduct[$i] = substr($sixOrder, $i, 1) * $rsMultiplier[$ixCurrentMultiplier];
            if ($ixCurrentMultiplier == 2) {
                $ixCurrentMultiplier = 0;
            } else {
                $ixCurrentMultiplier++;
            }
        }
        $sumProduct = 0;
        foreach ($rsProduct as $product) {
            $sumProduct += $product;
        }
        if ($sumProduct % 10 == 0) {
            $ixReference = 0;
        } else {
            $ixReference = 10 - ($sumProduct % 10);
        }
        return $sixOrder . $ixReference;

        
    }

    /**
     * Accessor for getting the total amount of the invoice.
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
     * Accessor for getting the total taxes charged to the invoice.
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
     * Accessor for getting the total amount (without taxes) for the invoice.
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
     * Retrieves the products inside this invoice.
     *
     * @return $this
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'invoice_product')->withPivot(['quantity', 'priceEach', 'taxPercent', 'isPriceEdited']);
    }

    /**
     * Retrieves the belonging order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Removes all the products from the current invoice.
     *
     * @return Invoice $this
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
     * Returns the returns made for this invoice.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function returns()
    {
        return $this->belongsToMany(Returned::class);
    }

    /**
     * Searches invoices.
     *
     * @param $string
     * @return mixed
     */
    public static function search($string)
    {
        return Proforma::with(['customer', 'products'])->where('id', str_replace('#', '', $string))->first();
    }
}