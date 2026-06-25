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
        return $this->su == 1;  // su=1 means admin 
    }

    public function isUser(): bool
    {
        return $this->su == 2;
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }
}