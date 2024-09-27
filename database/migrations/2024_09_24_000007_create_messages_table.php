<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id('message_id'); // Automatically an unsignedBigInteger
            $table->text('content');
            $table->datetime('appointment')->nullable();
            $table->string('status')->check(DB::raw('status in ("active", "offline")'))->default('active');
            $table->datetime('date');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('conversation_id')->nullable();

            // Define foreign keys directly here
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('conversation_id')->references('conversation_id')->on('conversations')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
};


