<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadOrder extends Model
{
    protected $table = 'upload_orders'; // Corrected to plural form
    protected $primaryKey = 'upID'; // Primary key
    public $timestamps = false; // Disable timestamps

    // Define the fillable properties
    protected $fillable = [
        'upName',
        'upQuantity',
        'upAmount',
        'user_id',
    ];

    // Relationship with Size
    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'upload_order_size', 'upID', 'sizeID')
            ->withPivot('quantity');
    }

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
