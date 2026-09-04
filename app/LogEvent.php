<?php

namespace App;

use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogEvent extends Model
{
    use SoftDeletes;

    public function thing()
    {
        return $this->morphTo('thing');
    }

    public function actor()
    {
        return $this->morphTo('actor');
    }
}