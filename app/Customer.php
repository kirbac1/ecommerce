<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;

// use App\Models\Model;
use App\Traits\GivesAuthorization;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    use HasFactory;
    use SoftDeletes, GivesAuthorization;

    protected $guard = 'customers';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that will be hidden.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at', 'remember_token', 'password',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'name', 'surname', 'email1', 'email2', 'website', 'phone', 'mobile', 'vatid', 'taxid', 'street1',
        'street2', 'city', 'state', 'zipcode', 'country', 'notes', 'discount', 'enabled', 'customer_group_id', 'company',
        'newsletter', 'reward_points',
    ];

    /**
     * List of the accessors that will be present inside the JSON results.
     *
     * @var array
     */
    protected $accessors = [
        'discountPercent', 'orders',
    ];

    /**
     * Returns the newsletters this customer belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function newsletters()
    {
        return $this->belongsToMany(NewsletterGroup::class);
    }

    /**
     * Returns the customer group he belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(CustomerGroup::class, "customer_group_id")->where('enabled', '!=', 0);
    }

    /**
     * Return the payments associated to this customer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Returns the proformas owned by this customer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function proformas()
    {
        return $this->hasMany(Proforma::class);
    }

    /**
     * Returns the invoices owned by this customer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Returns the orders made by this customer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class)->with('products');
    }

    /**
     * Returns the returns of this customer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function returns()
    {
        return $this->hasMany(Returned::class);
    }

    public function getCompanyAttribute()
    {
        if ($this->type === 'company') return $this->attributes['company'];
        else return null;
    }

    /**
     * Returns the discount percent for this user.
     *
     * @return mixed
     */
    public function getDiscountPercentAttribute()
    {
        $group = $this->group;
        if ($group) {
            return $group->discountPercent;
        } else {
            return 0;
        }
    }

    // Accessor to handle authorization.
    public function getIsCustomerAttribute() { return $this->exists; }

    /**
     * Add custom accessors to JSON.
     *
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();
        if ($this->group !== null) {
            $array['customer_group'] = $this->group->name;
        } else {
            $array['customer_group'] = null;
        }
        $array['discountPercent'] = $this->discountPercent;
        return $array;
    }

    /**
     * Searches for a customers with given attributes.
     *
     * @param $string
     * @return mixed
     */
    public static function search($string, $start=0, $limit=40)
    {
        return Customer::like([
            'name', 'surname', 'email1', 'email2', 'phone', 'mobile', 'vatid', 'taxid'
        ], "%$string%")->with('orders')->skip($start)->take($limit)->get();
    }

    public function scopeLike($query, $fields, $value)
    {
        return $this->scopeOrLike($query, $fields, $value);
    }

    /**
     * Add "OR Like" clause to the query.
     *
     */
    public function scopeOrLike($query, $fields, $value)
    {
        $newQuery = $query;
        if (!is_array($fields)) { $fields = [$fields]; }
        foreach($fields as $field)
        {
            $newQuery = $query->orWhere($field, 'LIKE', $value);
        }
        return $newQuery;
    }

    /**
     * Add an "AND Like" clause to the query.
     *
     */
    public function scopeAndLike($query, $fields, $value)
    {
        $newQuery = $query;
        if (!is_array($fields)) { $fields = [$fields]; }
        foreach($fields as $field)
        {
            $newQuery = $query->where($field, 'LIKE', $value);
        }
        return $newQuery;
    }
}