<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id('messID'); // Primary key as messID
            $table->text('messContent'); // Message content
            $table->datetime('messDate'); // Date and time of message
            $table->unsignedBigInteger('user_id'); // User ID (sender)
            $table->unsignedBigInteger('convoID'); // Conversation ID

            // Define foreign keys directly here
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('convoID')->references('convoID')->on('conversations')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
