<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('orderID');
            $table->integer('orderTotal');
            $table->string('orderStatus')->default('Pending');
            $table->integer('orderQuantity');
            $table->date('dateOrder');
            $table->date('dateReceived')->nullable();
            $table->unsignedBigInteger('user_id'); // User foreign key
            $table->unsignedBigInteger('convoID');
            $table->unsignedBigInteger('collectID')->nullable(); // Nullable
            $table->unsignedBigInteger('customID')->nullable(); // Nullable
            $table->unsignedBigInteger('upID')->nullable(); // Nullable

            // Foreign key constraints
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('convoID')->references('convoID')->on('conversations')->onDelete('cascade');
            $table->foreign('collectID')->references('collectID')->on('collections')->onDelete('set null');
            $table->foreign('customID')->references('customID')->on('custom_orders')->onDelete('set null');
            $table->foreign('upID')->references('upID')->on('upload_orders')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
