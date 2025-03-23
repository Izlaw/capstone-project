<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('models', function (Blueprint $table) {
            $table->id('modelID'); // auto-increment primary key
            $table->string('modelName'); // name of the model (tshirt, polo, etc.)
            $table->string('modelFilePath'); // path to the model file (GLTF, OBJ, etc.)
        });

        DB::table('models')->insert([
            ['modelName' => 'tshirt', 'modelFilePath' => '/models/tshirt.gltf'],
            ['modelName' => 'polo', 'modelFilePath' => '/models/polo.gltf'],
            // Add more models as needed
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('models');
    }
};
