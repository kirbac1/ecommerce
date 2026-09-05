<?php

namespace App;

use App\Models\Model;

class NewsletterGroup extends Model
{
    protected $table = 'newsletter_groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
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
        return $this->belongsToMany(Customer::class);
    }

    /**
     * Returns the newsletters belonging to this group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function newsletters()
    {
        return $this->hasMany(Newsletter::class);
    }

    /**
     * Searches for customer groups with given attributes.
     *
     * @param $string
     * @return mixed
     */
    public static function search($string, $start=0, $limit=40)
    {
        return NewsletterGroup::like([
            'name'
        ], "%$string%")->skip($start)->take($limit)->get();
    }
}