<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('custom_orders', function (Blueprint $table) {
            $table->id();
            $table->string('color');           // Store color customization
            $table->string('collar_type');     // Store collar type customization
            // Add any other customization fields here
            $table->timestamps();              // For created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('custom_orders');
    }
}

