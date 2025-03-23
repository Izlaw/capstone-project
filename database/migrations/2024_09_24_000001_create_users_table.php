<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('customer');
            $table->string('sex')->check(DB::raw('sex in ("Male", "Female", "Other")'));
            $table->date('bday');
            $table->string('contact');
            $table->string('address');
        });

        // Insert default users
        DB::table('users')->insert([
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@example.com',
                'password' => bcrypt('12345678'), // Change as needed
                'role' => 'admin',
                'sex' => 'Male',
                'bday' => '1980-01-01',
                'contact' => '123-456-7890',
                'address' => '123 Admin Street',
            ],
            [
                'first_name' => 'Customer',
                'last_name' => 'One',
                'email' => 'nyctuss@gmail.com',
                'password' => bcrypt('12345678'),
                'role' => 'customer',
                'sex' => 'Male',
                'bday' => '2000-01-01',
                'contact' => '098-765-4321',
                'address' => '123 Customer Blvd.',
            ],
            [
                'first_name' => 'Customer',
                'last_name' => 'Two',
                'email' => 'customer2@example.com',
                'password' => bcrypt('12345678'),
                'role' => 'customer',
                'sex' => 'Female',
                'bday' => '1990-01-01',
                'contact' => '098-123-4321',
                'address' => '456 Customer Avenue.',
            ],
            [
                'first_name' => 'Employee',
                'last_name' => 'One',
                'email' => 'employee1@example.com',
                'password' => bcrypt('12345678'),
                'role' => 'employee',
                'sex' => 'Male',
                'bday' => '1985-01-01',
                'contact' => '567-890-1234',
                'address' => '789 Employee Road',
            ],
            [
                'first_name' => 'Employee',
                'last_name' => 'Two',
                'email' => 'employee2@example.com',
                'password' => bcrypt('12345678'),
                'role' => 'employee',
                'sex' => 'Female',
                'bday' => '1990-01-01',
                'contact' => '123-456-7891',
                'address' => '456 Employee Street',
            ],
        ]);
    }

    public function down()
    {
        DB::table('users')->whereIn('email', [
            'admin@example.com',
            'customer1@example.com',
            'customer2@example.com',
            'employee1@example.com',
            'employee2@example.com',
        ])->delete();

        Schema::dropIfExists('users');
    }
};
