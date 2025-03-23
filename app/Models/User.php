<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;  // Add this trait to make User model notifiable

    protected $primaryKey = 'user_id';
    public $timestamps = false;   // Disable timestamps as per the schema
    protected $table = 'users';    // Ensure the table is 'users' (not 'user')

    // Fillable fields for mass assignment
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'sex',
        'bday',
        'contact',
        'address',
    ];

    // Hidden attributes for serialization (to hide password and remember_token)
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Custom methods for checking roles
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

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id'); // Make sure this is the correct foreign key
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'user_id', 'user_id');
    }

    public function fullCustomerName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getFullCustomerNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'user_id'); // Assuming 'user_id' is the foreign key
    }
}
