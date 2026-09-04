<?php

namespace App;

use App\Models\Model;
use App\Traits\BelongsToUser;

class TicketThread extends Model
{
    use BelongsToUser;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'user_id', 'subject', 'department', 'status',
    ];

    /**
     * Sets the table for the tickets.
     *
     * @var string
     */
    protected $table = 'tickets';

    /**
     * The attributes that will be hidden.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at',
    ];

    /**
     * Returns the messages in this ticket.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(TicketMessage::class, 'thread_id');
    }

    /**
     * Searches for a ticket thread with given attributes.
     *
     * @param $string
     * @return mixed
     */
    public static function search($string = '', $start=0, $limit=20)
    {
        if ($string !== '') {
            return TicketThread::like('code', "%$string%")->skip($start)->take($limit)->get();
        } else {
            return TicketThread::skip($start)->take($limit)->get();
        }
    }
}