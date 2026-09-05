<?php

namespace App;

use App\Models\Model;
use App\Traits\HasManyProducts;
use Illuminate\Support\Str;
use Kalnoy\Nestedset\NodeTrait;

/**
 * A category in the shop's tree.
 *
 * Was baum/baum, which has no PHP 8 release. kalnoy/nestedset is the
 * maintained equivalent and stores the same nested-set structure, so the
 * existing `lft`/`rgt`/`parent_id` columns are kept as-is via the getters
 * below rather than migrating every row to its `_lft`/`_rgt` defaults.
 */
class Category extends Model
{
    use HasManyProducts;
    use NodeTrait;

    protected $table = 'categories';

    protected $fillable = ['name', 'parent_id', 'slug', 'deletable'];

    /**
     * The attributes that will be hidden.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at', 'slug', 'rgt', 'lft', 'depth',
    ];

    /**
     * List of the accessors that will be present inside the JSON results.
     *
     * @var array
     */
    protected $accessors = ['deletable'];

    /**
     * Fill in the slug from the name.
     *
     * The column is NOT NULL with no default. Under Laravel 5.2 this went
     * unnoticed because that config ran MySQL with strict mode off, so an
     * unset slug was quietly stored as ''. Strict mode is on now, so it is
     * populated properly instead of loosening the database again.
     */
    protected static function booted(): void
    {
        static::saving(function (Category $category) {
            if (empty($category->slug) && ! empty($category->name)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    // --- Nested set column names, matching the 2016 migration ----------------

    public function getLftName()
    {
        return 'lft';
    }

    public function getRgtName()
    {
        return 'rgt';
    }

    public function getParentIdName()
    {
        return 'parent_id';
    }

    /**
     * `deletable` is a tinyint in the database; expose it as a real boolean.
     */
    public function getDeletableAttribute()
    {
        if (array_key_exists('deletable', $this->attributes)) {
            return $this->attributes['deletable'] == '1';
        }

        return false;
    }

    /**
     * Searches categories with the given string.
     *
     * @param string $string
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function search($string, $start = 0, $limit = 40)
    {
        return Category::like(['name'], "%$string%")->skip($start)->take($limit)->get();
    }
}
