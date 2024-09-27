<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $table = 'conversations'; // Specify the table if it's different

    // Specify the primary key if it's not 'id'
    protected $primaryKey = 'conversation_id';

    // Define the fillable attributes
    protected $fillable = ['user_id'];
}
