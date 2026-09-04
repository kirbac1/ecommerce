<?php
namespace App;

use App\Traits\HasManyProducts;
use Baum\Node;

class Category extends Node
{
    use HasManyProducts;

    /**
     * The attributes that will be hidden.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at', 'slug', 'deleted_at', 'rgt', 'lft', 'depth'
    ];

    /**
     * List of the accessors that will be present inside the JSON results.
     *
     * @var array
     */
    protected $accessors = ['deletable'];

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'categories';

    //////////////////////////////////////////////////////////////////////////////

    //
    // Below come the default values for Baum's own Nested Set implementation
    // column names.
    //
    // You may uncomment and modify the following fields at your own will, provided
    // they match *exactly* those provided in the migration.
    //
    // If you don't plan on modifying any of these you can safely remove them.
    //

    // /**
    //  * Column name which stores reference to parent's node.
    //  *
    //  * @var string
    //  */
    // protected $parentColumn = 'parent_id';

    // /**
    //  * Column name for the left index.
    //  *
    //  * @var string
    //  */
    // protected $leftColumn = 'lft';

    // /**
    //  * Column name for the right index.
    //  *
    //  * @var string
    //  */
    // protected $rightColumn = 'rgt';

    // /**
    //  * Column name for the depth field.
    //  *
    //  * @var string
    //  */
    // protected $depthColumn = 'depth';

    // /**
    //  * Column to perform the default sorting
    //  *
    //  * @var string
    //  */
    // protected $orderColumn = null;

    // /**
    // * With Baum, all NestedSet-related fields are guarded from mass-assignment
    // * by default.
    // *
    // * @var array
    // */
    // protected $guarded = array('id', 'parent_id', 'lft', 'rgt', 'depth');

    //
    // This is to support "scoping" which may allow to have multiple nested
    // set trees in the same database table.
    //
    // You should provide here the column names which should restrict Nested
    // Set queries. f.ex: company_id, etc.
    //

    // /**
    //  * Columns which restrict what we consider our Nested Set list
    //  *
    //  * @var array
    //  */
    // protected $scoped = array();

    //////////////////////////////////////////////////////////////////////////////

    //
    // Baum makes available two model events to application developers:
    //
    // 1. `moving`: fired *before* the a node movement operation is performed.
    //
    // 2. `moved`: fired *after* a node movement operation has been performed.
    //
    // In the same way as Eloquent's model events, returning false from the
    // `moving` event handler will halt the operation.
    //
    // Please refer the Laravel documentation for further instructions on how
    // to hook your own callbacks/observers into this events:
    // http://laravel.com/docs/5.0/eloquent#model-events

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
                $array[$accessor] = $this[$accessor];
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
     * Accessor for the deletable attribute.
     *
     * @return bool
     */
    public function getDeletableAttribute()
    {
        if (isset($this->attributes['deletable']) || array_key_exists('deletable', $this->attributes)) {
            return $this->attributes['deletable'] == '1';
        } else {
            return false;
        }
    }

    /**
     * Searches for categories with given attributes.
     *
     * @param $string
     * @return mixed
     */
    public static function search($string, $start=0, $limit=40)
    {
        return Category::like(['name'], "%$string%")->skip($start)->take($limit)->get();
    }
}
