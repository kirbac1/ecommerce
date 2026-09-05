<?php

namespace App;

use App\Traits\GivesAuthorization;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use GivesAuthorization;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'demo',
        'name', 'surname', 'email', 'password', 'enabled', 'type', 'language',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'deleted_at'
    ];

    // Accessor to handle authorization.
    public function getIsUserAttribute() { return $this->exists; }
    public function getIsAdminAttribute() { return ($this->type === 'admin'); }
    public function getIsSuperAdminAttribute() { return ($this->type === 'admin') && ($this->superAdmin); }

    /**
     * Returns the tickets owned by this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany(TicketThread::class);
    }

    public function getNameAndSurnameAttribute()
    {
        return $this->name . ' ' . $this->surname;
    }

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
     * Searches for users with given attributes.
     *
     * @param $string
     * @return mixed
     */
    public static function search($string, $start=0, $limit=20)
    {
        return User::like([
            'name', 'surname', 'email'
        ], "%$string%")->skip($start)->take($limit)->get();
    }

}
