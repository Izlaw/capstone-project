<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->id('collectID');
            $table->string('collectName', 50);
            $table->integer('collectPrice');
            $table->string('collectFilePath', 255);
        });

        // Insert initial data
        DB::table('collections')->insert([
            [
                'collectName' =>  'White Polo T-shirt',
                'collectPrice' => 1000,
                'collectFilePath' => 'collections/polo-tshirt.png',
            ],
            [
                'collectName' => 'Black T-shirt',
                'collectPrice' => 800,
                'collectFilePath' => 'collections/black-tshirt.png',
            ],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('collections');
    }
};
