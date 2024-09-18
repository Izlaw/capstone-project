<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    // Specify the table if it's not following Laravel's naming convention
    protected $table = 'messages';
    protected $primaryKey = 'message_id';
    protected $fillable = [
        'content', 
        'date', 
        'status', 
        'user_id',
        'conversation_id',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}

