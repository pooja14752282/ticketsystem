<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'ticket_id',                 // ← added: varchar NOT NULL, must be fillable
        'title',                     // ← added: varchar NOT NULL, must be fillable
        'description',
        'attachment',
        'creator_email',             // ← added: varchar NOT NULL, must be fillable
        'type',
        'priority',
        'app_name',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
        'status',
        'assigned_to',
        'assigned_team_member_id',
        'category',
        'category_id',
        'due_date',
        'due_date_reason',
        'reassign_reason',
    ];

    // Ticket creator
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Ticket assignee
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Ticket category relationship
    public function ticketCategory()
    {
        return $this->belongsTo(TicketCategory::class, 'category_id');
    }

    // Age in hours
    public function getAgeAttribute(): int
    {
        return (int) $this->created_at->diffInHours(now());
    }

    // Assigned support team member
    public function assignedTeamMember()
    {
        return $this->belongsTo(TicketSupportTeam::class, 'assigned_team_member_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function review()
    {
        return $this->hasOne(TicketReview::class);
    }
}