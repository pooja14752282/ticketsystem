<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTeam extends Model
{
    protected $fillable = ['name', 'email', 'app_assigned', 'is_active'];

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