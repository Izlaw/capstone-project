<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('custom_order_size', function (Blueprint $table) {
            $table->id('customsizeID'); // Primary key
            $table->unsignedBigInteger('customID'); // Foreign key to custom orders table
            $table->unsignedBigInteger('sizeID'); // Foreign key to sizes table
            $table->integer('quantity');

            // Foreign keys
            $table->foreign('customID')->references('customID')->on('custom_orders')->onDelete('cascade');
            $table->foreign('sizeID')->references('sizeID')->on('sizes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('custom_order_size');
    }
};
