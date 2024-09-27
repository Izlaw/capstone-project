<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
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

        // Insert default users
        DB::table('users')->insert([
            // Add your user data here as needed
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};

