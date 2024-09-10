<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category', function (Blueprint $table) {
            $table->id('cat_ID');
            $table->string('cat_Type')->check(DB::raw('cat_Type in ("School Uniform", "Sports Wear", "T-Shirts", "Curtains", "Accessories")'));
            $table->float('cat_Price');
            $table->binary('cat_File');
        });

        Schema::create('feedback', function (Blueprint $table) {
            $table->id('feedbackID');
            $table->float('ratings')->check(DB::raw('ratings >= 1.0 and ratings <= 5.0'));
            $table->text('comments');
            $table->integer('user_id');
            $table->integer('orderID');
        });

        Schema::create('materials', function (Blueprint $table) {
            $table->id('materialID');
            $table->string('materialName');
            $table->string('materialType');
            $table->string('materialColor');
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id('message_id');
            $table->text('content');
            $table->datetime('appointment');
            $table->string('status')->check(DB::raw('status in ("active", "offline")'));
            $table->datetime('date');
            $table->integer('user_id');
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id('orderID');
            $table->integer('totalPrice');
            $table->string('orderStatus')->check(DB::raw('orderStatus in ("Pending", "In Progress", "Ready for Pickup", "Completed", "Cancelled")'));
            $table->integer('orderTotal');
            $table->date('dateOrder');
            $table->date('dateReceived');
            $table->integer('user_id');
            $table->integer('productID');
        });

        Schema::create('product', function (Blueprint $table) {
            $table->id('productID');
            $table->string('productName');
            $table->float('productPrice');
            $table->binary('productFile');
            $table->integer('cat_ID');
            $table->integer('materialID');
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->closure(function ($field) {
                $field->check(DB::raw('role in ("admin", "customer", "employee")'))->default('customer');
            })->change();
            $table->string('sex')->check(DB::raw('sex in ("Male", "Female")'));
            $table->date('bday');
            $table->integer('contact');
            $table->string('address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('product');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('feedback');
        Schema::dropIfExists('category');
    }
};