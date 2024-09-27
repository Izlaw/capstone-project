<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->id('productID');
            $table->string('productName');
            $table->float('productPrice');
            $table->binary('productFile');
            $table->unsignedBigInteger('cat_ID');
            $table->unsignedBigInteger('materialID');
        });

        Schema::table('product', function (Blueprint $table) {
            $table->foreign('cat_ID')->references('cat_ID')->on('category')->onDelete('cascade');
            $table->foreign('materialID')->references('materialID')->on('materials')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('product');
    }
};

