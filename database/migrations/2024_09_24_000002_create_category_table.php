<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('category', function (Blueprint $table) {
            $table->id('cat_ID');
            $table->string('cat_Type')->check(DB::raw('cat_Type in ("School Uniform", "Sports Wear", "T-Shirts", "Curtains", "Accessories")'));
            $table->float('cat_Price');
            $table->binary('cat_File');
        });
    }

    public function down()
    {
        Schema::dropIfExists('category');
    }
};

