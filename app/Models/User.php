<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class User extends Model
{
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

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}
