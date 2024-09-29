<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id('feedbackID');
            $table->float('ratings')->check(DB::raw('ratings >= 1.0 and ratings <= 5.0'));
            $table->text('comments');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('orderID');
        });

        Schema::table('feedback', function (Blueprint $table) {
            $table->foreign('orderID')->references('orderID')->on('orders')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('feedback');
    }
};

