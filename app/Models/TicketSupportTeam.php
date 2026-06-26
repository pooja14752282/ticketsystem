<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class TicketSupportTeam extends Model
{
    protected $table = 'ticket_support_teams';
    public $timestamps = false;

    protected $fillable = ['user_id', 'name', 'email', 'app_assigned', 'is_active'];

    const APPS = [
        'seelinfinity'   => 'SeelInfinity',
        'examinfinity'   => 'ExamInfinity',
        'mockinfinity'   => 'MockInfinity',
        'dasohainfinity' => 'DasohaInfinity',
        'interninfinity' => 'InternInfinity',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_team_member_id');
    }
}
