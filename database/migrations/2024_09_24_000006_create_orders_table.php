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
            $table->integer('totalPrice');
            $table->string('orderStatus')->check(DB::raw('orderStatus in ("Pending", "In Progress", "Ready for Pickup", "Completed", "Cancelled")'));
            $table->integer('orderTotal');
            $table->date('dateOrder');
            $table->date('dateReceived');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('productID');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('productID')->references('productID')->on('product')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};

