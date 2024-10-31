<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomOrdersTable extends Migration
{
    public function up()
    {
        // Change the table name to customorders
        Schema::create('customorders', function (Blueprint $table) {
            $table->id();
            $table->string('color');           // Store color customization
            $table->string('collar_type');     // Store collar type customization
            $table->integer('size_xs')->default(0); // Size XS quantity
            $table->integer('size_s')->default(0);  // Size S quantity
            $table->integer('size_m')->default(0);  // Size M quantity
            $table->integer('size_l')->default(0);  // Size L quantity
            $table->integer('size_xl')->default(0); // Size XL quantity
            $table->integer('total')->default(0); // Total quantity of all sizes
            // Add any other customization fields here
            $table->timestamps();              // For created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('customorders'); // Drop the customorders table
    }
}
