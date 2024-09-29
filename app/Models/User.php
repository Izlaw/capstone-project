<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'user_id';
    public $incrementing = true; 

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

    public function isAdmin()
    {
        return $this->role === 'admin'; 
    }

    public function isEmployee()
    {
        return $this->role === 'employee'; 
    }

    public function isCustomer()
    {
        return $this->role === 'customer'; 
    }
}

