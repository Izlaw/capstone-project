<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    protected $table = 'sizes'; // Table name
    protected $primaryKey = 'sizeID'; // Primary key
    public $timestamps = false; // Disable timestamps

    protected $fillable = [
        'sizeName',
        'sizePrice',
    ];

    public function uploadOrders()
    {
        return $this->belongsToMany(UploadOrder::class, 'upload_order_size', 'sizeID', 'upID')
            ->withPivot('quantity');
    }
}
