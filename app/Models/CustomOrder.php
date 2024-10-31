<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomOrder extends Model
{
    use HasFactory;

    protected $table = 'customorders';
    protected $fillable = [
        'color', 
        'collar_type', 
        'size_xs', 
        'size_s', 
        'size_m', 
        'size_l', 
        'size_xl',
        'total',
    ];
}
