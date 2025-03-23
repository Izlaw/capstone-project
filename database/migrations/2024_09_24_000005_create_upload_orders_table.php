<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('upload_orders', function (Blueprint $table) {
            $table->id('upID'); // Primary key
            $table->string('upName', 255);
            $table->integer('upQuantity');
            $table->decimal('upAmount', 10, 2);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('upload_orders');
    }
};
