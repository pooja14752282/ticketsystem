<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'uid',
        'title',
        'message',
        'click_action',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'uid');
    }
}