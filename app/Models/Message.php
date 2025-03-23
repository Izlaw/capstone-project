<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';
    protected $primaryKey = 'messID';
    public $timestamps = false;

    protected $fillable = [
        'messContent',
        'messDate',
        'user_id',
        'convoID',
    ];

    // Ensure messDate is treated as a Carbon instance
    protected $casts = [
        'messDate' => 'datetime',
    ];

    // Relationship with User model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Relationship with Conversation model
    public function conversation()
    {
        return $this->belongsTo(Conversation::class, 'convoID', 'convoID');
    }
}
