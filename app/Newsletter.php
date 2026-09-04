<?php

namespace App;

use App\Models\Model;

class Newsletter extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'language', 'content', 'completed_at', 'scheduled_at', 'launched_at',
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
    public function groups()
    {
        return $this->belongsToMany(NewsletterGroup::class);
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
            'title'
        ], "%$string%")->skip($start)->take($limit)->get();
    }
}