<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketOption extends Model
{
    protected $fillable = [
        'type',
        'value',
        'label',
        'color',
        'text_color',
        'is_active',
        'sort_order',
    ];

    // Default status options
    public static function defaultStatuses()
    {
        return [
            ['value' => 'in_progress', 'label' => 'In Progress', 'color' => '#dbeafe', 'text_color' => '#1e40af'],
            ['value' => 'completed',   'label' => 'Completed',   'color' => '#dcfce7', 'text_color' => '#166534'],
            ['value' => 'on_hold',     'label' => 'On Hold',     'color' => '#fef3c7', 'text_color' => '#92400e'],
            ['value' => 're_opened',   'label' => 'Re Opened',   'color' => '#ede9fe', 'text_color' => '#5b21b6'],
        ];
    }

    // Default priority options
    public static function defaultPriorities()
    {
        return [
            ['value' => 'low',    'label' => 'Low',    'color' => '#f0fdf4', 'text_color' => '#166634'],
            ['value' => 'high',   'label' => 'High',   'color' => '#dbeafe', 'text_color' => '#1e40af'],
            ['value' => 'urgent', 'label' => 'Urgent', 'color' => '#fee2e2', 'text_color' => '#991b1b'],
        ];
    }
}