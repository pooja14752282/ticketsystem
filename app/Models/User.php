<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $connection = 'examai';
    protected $table = 'users';
    protected $primaryKey = 'uid';  
    public $timestamps = false;  

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'gid',
        'su',
    ];

    protected $hidden = [
        'password',
    ];

    // Full name accessor
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function isAdmin(): bool
{
    return $this->su == 1;
}

public function isSupportTeam(): bool
{
    return $this->su == 4;
}

public function hasRole(string $role): bool
{
    $roleMap = [
        'admin'   => 1,
        'support' => 4,
    ];

    return isset($roleMap[$role]) && $this->su == $roleMap[$role];
}
}