<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateControlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('controls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('item_id')->index();
            $table->unsignedBigInteger('vehicle_id')->index();
            $table->unsignedSmallInteger('year')->default(0);
            $table->unsignedSmallInteger('maxi')->default(0);
            $table->unsignedSmallInteger('used')->default(0);
            $table->unsignedSmallInteger('blnc')->default(0);
            $table->timestamps();

            $table->unique(['item_id', 'vehicle_id', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('controls');
    }
}
