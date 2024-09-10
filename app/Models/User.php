<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'user_id';
    public $incrementing = true; // Set to false if your primary key is not auto-incrementing
    protected $keyType = 'int'; // Change if your primary key is not an integer

    public $timestamps = false;
    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'sex',
        'bday',
        'contact',
        'address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}

