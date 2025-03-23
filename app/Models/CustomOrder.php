<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomOrder extends Model
{
    use HasFactory;

    protected $table = 'custom_orders'; // Table name
    protected $primaryKey = 'customID'; // Primary key
    public $timestamps = false; // Disable timestamps

    protected $fillable = [
        'colors',
        'text',
        'customQuantity',
        'fabric_type',
        'user_id',
        'totalAmount',
    ];

    protected $casts = [
        'colors' => 'array', // Cast colors to array
        'text' => 'array'
    ];

    // Define the many-to-many relationship with Size
    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'custom_order_size', 'customID', 'sizeID')
            ->withPivot('quantity');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customID', 'customID');
    }
}
