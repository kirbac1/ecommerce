<?php

namespace App\Models;

class Model extends \Illuminate\Database\Eloquent\Model
{
    /**
     * List of the accessors that will be present inside the JSON results.
     *
     * @var array
     */
    protected $accessors = [];

    /**
     * Add the accessors, if they're requested.
     *
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();

        foreach($this->accessors as $accessor) {
            if (!in_array($accessor, $this->hidden)) {  // If it's not hidden
                $array[$accessor] = $this->{$accessor};
            }
        }

        return $array;
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

    /**
     * Overrides the default unoptimized behaviour.
     *
     * @return bool
     */
    public function exists()
    {
        return $this->id && ($this->deleted_at === null);
    }
}