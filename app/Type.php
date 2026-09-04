<?php

namespace App;

use App\Models\Model;

class Type extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type'
    ];

    /**
     * The attributes that will be hidden.
     *
     * @var array
     */
    protected $hidden = [
        ''
    ];

    /**
     * Returns the measure units using this type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function measureunits()
    {
        return $this->hasMany(Type::class);
    }

    /**
     * Searches for a type with given attributes.
     *
     * @param $string
     * @return mixed
     */
    public static function search($string, $start=0, $limit=20)
    {
        return Type::like([
            'type', 'name'
        ], "%$string%")->skip($start)->take($limit)->get();
    }
}