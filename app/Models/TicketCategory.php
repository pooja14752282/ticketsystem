<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'assign_to',
        'email',
        'status',
        'app_name',
    ];

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assign_to');
    }
}