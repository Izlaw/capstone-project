<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('collection_order_size', function (Blueprint $table) {
            $table->id('collectionordersizeID');
            $table->unsignedBigInteger('orderID');
            $table->unsignedBigInteger('collectID');
            $table->unsignedBigInteger('sizeID');
            $table->integer('quantity');

            $table->foreign('orderID')->references('orderID')->on('orders')->onDelete('cascade');
            $table->foreign('collectID')->references('collectID')->on('collections')->onDelete('cascade');
            $table->foreign('sizeID')->references('sizeID')->on('sizes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('collection_order_size');
    }
};
