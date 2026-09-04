<?php

namespace App;

use App\Models\Model;
use app\Traits\BelongsToUser;

class TicketMessage extends Model
{
    use BelongsToUser;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'content',
    ];

    /**
     * The attributes that will be hidden.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at',
    ];

    /**
     * Returns the parent thread.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thread()
    {
        return $this->belongsTo(TicketThread::class);
    }

    /**
     * Searches for a ticket message with given attributes.
     *
     * @param $string
     * @return mixed
     */
    public static function search($string, $start=0, $limit=20)
    {
        return TicketMessage::like([
            'content'
        ], "%$string%")->skip($start)->take($limit)->get();
    }
}