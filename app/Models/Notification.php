<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'ticket_notifications';

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'ticket_id',
        'is_read',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'uid');
    }
}