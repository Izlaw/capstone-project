<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sizes', function (Blueprint $table) {
            $table->id('sizeID');         // Primary key for size
            $table->string('sizeName');    // Name of the size (e.g., 'XS', 'Small', etc.)
            $table->integer('sizeQuantity')->default(0); // Column for size quantity
            $table->decimal('sizePrice', 10, 2); // New column for size price
        });

        // Insert initial size data directly in the migration
        DB::table('sizes')->insert([
            ['sizeName' => '4XS', 'sizePrice' => 200.00],
            ['sizeName' => '3XS', 'sizePrice' => 220.00],
            ['sizeName' => '2XS', 'sizePrice' => 240.00],
            ['sizeName' => 'XS', 'sizePrice' => 260.00],
            ['sizeName' => 'S', 'sizePrice' => 280.00],
            ['sizeName' => 'M', 'sizePrice' => 300.00],
            ['sizeName' => 'L', 'sizePrice' => 320.00],
            ['sizeName' => 'XL', 'sizePrice' => 340.00],
            ['sizeName' => 'XXL', 'sizePrice' => 360.00],
            ['sizeName' => 'XXXL', 'sizePrice' => 380.00],
            ['sizeName' => '4XL', 'sizePrice' => 400.00],
            ['sizeName' => '5XL', 'sizePrice' => 420.00],
            ['sizeName' => '6XL', 'sizePrice' => 440.00],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('sizes');
    }
};
