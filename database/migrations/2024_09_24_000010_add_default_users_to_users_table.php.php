<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
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
    }
};

