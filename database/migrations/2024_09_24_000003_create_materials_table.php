<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id('materialID');
            $table->string('materialName');
            $table->string('materialType');
            $table->string('materialColor');
        });
    }

    public function down()
    {
        Schema::dropIfExists('materials');
    }
};

