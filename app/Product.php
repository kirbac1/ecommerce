<?php

namespace App;


use App\Models\Model;
use App\Traits\HidesAttributes;
use App\Traits\BelongsToCategory;
use App\Traits\ShowsAttributes;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
class Product extends Model
{
    use BelongsToCategory, HidesAttributes, ShowsAttributes;
    use SoftDeletes;
    protected $discountPercent = 0.00, $discountAmount = 0.00;

    /**
     * Eager loading of some relationships.
     *
     * @var array
     */
    protected $with = ['manufacturer', 'measureunit'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'manufacturer_id', 'image', 'category_id', 'name', 'visible', 'measureunit_id', 'basePrice', 'qtyPerPack', 'taxPercent', 'sku', 'barcode', 'priceEach','discountPercent'
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
     * List of the accessors that will be present inside the JSON results.
     *
     * @var array
     */
    protected $accessors = [
        'taxAmount', 'taxPercent','qtyPerPack', 'taxedPrice', 'priceEach', // 'signature',
    ];

    /**
     * Returns the manufacturer of the item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }

    /**
     * Returns the warehouses where the item is stored.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class);
    }

    /**
     * Returns the options of this item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function options()
    {
        return $this->hasMany(ProductOption::class);
    }

    /**
     * Returns the discounts available for this item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

    /**
     * Returns the proformas involving this item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function proformas()
    {
        return $this->belongsToMany(Proforma::class);
    }

    /**
     * Returns the invoices involving this item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function invoices()
    {
        return $this->belongsToMany(Invoice::class);
    }

    /**
     * Returns the orders for this item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }

    /**
     * Returns the returns of this item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function returns()
    {
        return $this->belongsToMany(Returned::class);
    }

    /**
     * Returns the measure unit of the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function measureunit()
    {
        return $this->belongsTo(Measureunit::class);
    }

    /**
     * Formats the pack quantity attribute.
     *
     * @return array|float
     */
    public function getQtyPerPackAttribute()
    {
        if (isset($this->measureunit_id)) {
            if ($this->measureunit->type->name == 'integer') {
                try {
                    return($this->original['qtyPerPack']);
                } catch(\Exception $e) {
                    return null;
                }
            } else {
                try {
                    return $this->original['qtyPerPack'];
                } catch(\Exception $e) {
                    return null;
                }
            }
        } else {
            try {
                return $this->original['qtyPerPack'];
            } catch(\Exception $e) {
                return null;
            }
        }
    }

    /**
     * PriceEach getter: it applies the customer discount to the product price.
     *
     * @return float
     */
    public function getPriceEachAttribute()
    {
        if ($this->exists()) {
            try {
                $debug = ($this->original['priceEach'] * (100 - $this->discountPercent) / 100) - $this->discountAmount;
                  Log::info('price each in getTaxedPriceAttribute: '. $debug );
                return $debug;
            } catch(\Exception $e) {
                return null;
            }
        } else {
            try {
                return ($this->attributes['priceEach'] * (100 - $this->discountPercent) / 100) - $this->discountAmount;
                
            } catch(\Exception $e) {
                return null;
            }
        }
    }

    /**
     * Returns the price for the product with gains and taxes.
     *
     * @return float
     */
    public function getTaxedPriceAttribute()
    {
      
        $debug = $this->priceEach * (100 + $this->taxPercent) / 100;
         Log::info('price each in getTaxedPriceAttribute: '. $debug );
         return $debug ;
    }


    /**
     * Returns the price for a single product, with gains and taxes added.
     *
     * @return float
     */
    public function getTaxAmountAttribute()
    {
        return $this->taxedPrice - $this->priceEach;
    }

    /**
     * Generate an hash of the current path to avoid attacks injecting weird stuff.
     *
     * @return mixed
     */
    public function getSignatureAttribute()
    {
        return Hash::make($this->image . Config::get('key'));
    }

    /**
     * Searches for a product with given attributes.
     *
     * @param $string
     * @return mixed
     */
    public static function search($string, $start=0, $limit=20)
    {
        // whereIn needs the ids, not the models -- passing the collection itself
        // silently matched nothing, so searching by brand always came back empty.
        $manufacturerIds = Manufacturer::like(['name'], "%$string%")->get()->pluck('id')->all();
        $ret = Product::with(['measureunits','measureunits.type','manufacturer'])->whereIn('manufacturer_id', $manufacturerIds)->like(['name', 'barcode', 'sku'], "%$string%")->orderBy('name','ASC')->get();
        return $ret->splice($start, $limit);
    }

    /**
     * Setter for discountAmount.
     *
     * @param $amount
     * @return $this
     */
    public function setDiscountAmountAttribute($amount)
    {
        $this->discountAmount = $amount;
        return $this;
    }

    /**
     * Setter for discountPercent.
     *
     * @param $discountPercent
     * @return $this
     */
    public function setDiscountPercentAttribute($discountPercent)
    {
        $this->discountPercent = floatval($discountPercent);
        return $this;
    }

    /**
     * Add accessors to JSON.
     *
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray(); // OPTIMIZE THIS
        $array['priceEach'] = formatter($this->priceEach);
        $array['taxPercent'] = formatter($this->taxPercent,2);
        $array['discountPercent'] = formatter($this->discountPercent);
       
        if (in_array('signature', $this->visible)) $array['signature'] = $this->signature;
        if (!in_array('taxedPrice', $this->hidden)) $array['taxedPrice'] = formatter(floatval($array['priceEach']) * (100 + floatval($this->taxPercent)) / 100);
        if (!in_array('taxAmount', $this->hidden)) $array['taxAmount'] = formatter($this->taxAmount);
        if (!in_array('manufacturer_id', $this->hidden)) $array['manufacturer_id'] = $this->manufacturer_id;
        try {
            if (!in_array('measureunit', $this->hidden)) $array['measureunit'] = $this->measureunit->name;
            if (!in_array('manufacturer', $this->hidden)) $array['manufacturer'] = $this->manufacturer->name;
        } catch (\Exception $e) {}

        return $array;
    }
}

function formatter($value, $decimals=4) {
    return number_format($value, $decimals, '.', '');
}
