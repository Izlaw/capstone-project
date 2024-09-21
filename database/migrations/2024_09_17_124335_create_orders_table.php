<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('ViewOrders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->decimal('total_price', 10, 2);
            $table->string('order_status');
            $table->integer('quantity');
            $table->date('date_ordered');
            $table->date('date_received');
            $table->string('product');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ViewOrders');
    }
}