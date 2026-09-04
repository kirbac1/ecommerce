<?php

namespace App;

use App\Models\Model;

class Measureunit extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type_id'
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
     * Returns the variable type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    /**
     * Searches for a measure unit with given attributes.
     *
     * @param $string
     * @return mixed
     */
    public static function search($string, $start=0, $limit=40)
    {
        return Measureunit::like([
            'name'
        ], "%$string%")->skip($start)->take($limit)->get();
    }
}