<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id('conversation_id'); // Automatically an unsignedBigInteger
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            
            // Define foreign key directly here
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('conversations');
    }
};
