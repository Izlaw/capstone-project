<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // drop the tables first if they exist
        Schema::dropIfExists('messages');
        Schema::dropIfExists('feedback');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('product');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('category');
        Schema::dropIfExists('users');
        Schema::dropIfExists('conversations'); // Drop if exists

        // Create tables
        Schema::create('category', function (Blueprint $table) {
            $table->id('cat_ID');
            $table->string('cat_Type')->check(DB::raw('cat_Type in ("School Uniform", "Sports Wear", "T-Shirts", "Curtains", "Accessories")'));
            $table->float('cat_Price');
            $table->binary('cat_File');
        });

        Schema::create('materials', function (Blueprint $table) {
            $table->id('materialID');
            $table->string('materialName');
            $table->string('materialType');
            $table->string('materialColor');
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'customer', 'employee'])->default('customer');
            $table->string('sex')->check(DB::raw('sex in ("Male", "Female")'));
            $table->date('bday');
            $table->string('contact');
            $table->string('address');
        });

        Schema::create('product', function (Blueprint $table) {
            $table->id('productID');
            $table->string('productName');
            $table->float('productPrice');
            $table->binary('productFile');
            $table->unsignedBigInteger('cat_ID');
            $table->unsignedBigInteger('materialID');
        });

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

        Schema::create('messages', function (Blueprint $table) {
            $table->id('message_id'); // Primary key for the messages table
            $table->text('content'); // The content of the message
            $table->datetime('appointment')->nullable(); // Optional
            $table->string('status')->check(DB::raw('status in ("active", "offline")'))->default('active'); // Optional
            $table->datetime('date'); // Date when the message was sent
            $table->unsignedBigInteger('user_id'); // Foreign key for the user
            $table->unsignedBigInteger('conversation_id')->nullable(); // New column
        });

        Schema::create('conversations', function (Blueprint $table) {
            $table->id('conversation_id'); // Primary key
            $table->unsignedBigInteger('user_id'); // Foreign key
            $table->timestamps();
        });

        Schema::create('feedback', function (Blueprint $table) {
            $table->id('feedbackID');
            $table->float('ratings')->check(DB::raw('ratings >= 1.0 and ratings <= 5.0'));
            $table->text('comments');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('orderID');
        });

        // Insert default users
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('12345678'), // Change as needed
                'role' => 'admin',
                'sex' => 'Male',
                'bday' => '1980-01-01',
                'contact' => '123-456-7890',
                'address' => '123 Admin Street',
            ],
            [
                'name' => 'Customer 1 User',
                'email' => 'customer1@example.com',
                'password' => bcrypt('12345678'), // Change as needed
                'role' => 'customer',
                'sex' => 'Male',
                'bday' => '2000-01-01',
                'contact' => '098-765-4321',
                'address' => '123 Customer Blvd.',
            ],
            [
                'name' => 'Customer 2 User',
                'email' => 'customer2@example.com',
                'password' => bcrypt('12345678'), // Change as needed
                'role' => 'customer',
                'sex' => 'Female',
                'bday' => '1990-01-01',
                'contact' => '098-123-4321',
                'address' => '456 Customer Avenue.',
            ],
            [
                'name' => 'Employee 1 User',
                'email' => 'employee1@example.com',
                'password' => bcrypt('12345678'), // Change as needed
                'role' => 'employee',
                'sex' => 'Male',
                'bday' => '1985-01-01',
                'contact' => '567-890-1234',
                'address' => '789 Employee Road',
            ],
            [
                'name' => 'Employee 2 User',
                'email' => 'employee2@example.com',
                'password' => bcrypt('12345678'), // Change as needed
                'role' => 'employee',
                'sex' => 'Female',
                'bday' => '1990-01-01',
                'contact' => '123-456-7891',
                'address' => '456 Employee Street',
            ],
        ]);

        // Add foreign key constraints
        Schema::table('product', function (Blueprint $table) {
            $table->foreign('cat_ID')->references('cat_ID')->on('category')->onDelete('cascade');
            $table->foreign('materialID')->references('materialID')->on('materials')->onDelete('cascade');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('productID')->references('productID')->on('product')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });

        Schema::table('feedback', function (Blueprint $table) {
            $table->foreign('orderID')->references('orderID')->on('orders')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('conversation_id')->references('conversation_id')->on('conversations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        // Drop foreign key constraints first
        Schema::table('feedback', function (Blueprint $table) {
            $table->dropForeign(['orderID']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['productID']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('product', function (Blueprint $table) {
            $table->dropForeign(['cat_ID']);
            $table->dropForeign(['materialID']);
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['conversation_id']);
        });

        // drop the tables if they exist
        Schema::dropIfExists('feedback');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('product');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('category');
        Schema::dropIfExists('users');
        Schema::dropIfExists('conversations'); // Drop the conversations table
    }
};
