<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomModel extends Model
{
    use HasFactory;

    protected $table = 'models'; // Table name
    protected $primaryKey = 'modelID'; // Primary key
    public $timestamps = false; // If you don't have timestamp columns

    protected $fillable = [
        'modelName',
        'modelFilePath',
    ];

    public function customOrders()
    {
        return $this->hasMany(CustomOrder::class, 'modelID', 'modelID');
    }
}
