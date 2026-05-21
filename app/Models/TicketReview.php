<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'support_member_id',
        'notes',
        'resolution_status',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function supportMember()
    {
        return $this->belongsTo(User::class, 'support_member_id');
    }
}