<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadOrderSizeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upload_order_size', function (Blueprint $table) {
            $table->id('uploadordersizeID');
            $table->unsignedBigInteger('upID');
            $table->unsignedBigInteger('sizeID');
            $table->integer('quantity');

            $table->foreign('upID')->references('upID')->on('upload_orders')->onDelete('cascade');
            $table->foreign('sizeID')->references('sizeID')->on('sizes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('upload_order_size');
    }
}
