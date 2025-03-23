<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $table = 'collections'; // Corrected to plural form
    protected $primaryKey = 'collectID';
    public $timestamps = false; // Set to true if you have timestamps in the collections table

    // Define the fillable properties
    protected $fillable = [
        'collectName',
        'collectPrice',
        'collectFilePath',
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'collection_order_size', 'collectID', 'orderID')
            ->withPivot('sizeID', 'quantity', 'collectionordersizeID');
    }
}
