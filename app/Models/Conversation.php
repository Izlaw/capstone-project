<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Events\ConversationCreated;

class Conversation extends Model
{
    protected $table = 'conversations'; // Specify the table name
    public $timestamps = false;
    protected $primaryKey = 'convoID'; // Correct primary key reference

    // Define the fillable attributes
    protected $fillable = [
        'user_id', // User who initiated the conversation
        'messID',
        'convoID',  // Message ID (if needed, adjust based on your schema)
    ];

    protected $dispatchesEvents = [
        'created' => ConversationCreated::class,
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id'); // Foreign key to users table
    }

    // Relationship with Message (if you want to access messages for this conversation)
    public function messages()
    {
        return $this->hasMany(Message::class, 'convoID', 'convoID'); // Make sure 'conversation_id' exists in the Message model
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class, 'convoID', 'convoID')->orderBy('messID', 'desc');
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'user_id', 'user_id'); // Assuming order is linked by user_id
    }
}
