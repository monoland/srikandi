<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('frame_numb')->index();
            $table->string('machine_numb')->index();
            $table->string('refs_numb')->nullable();
            $table->unsignedBigInteger('brand_id')->index();
            $table->unsignedBigInteger('type_id')->index();
            $table->enum('kind', ['motor', 'mobil'])->index();
            $table->unsignedSmallInteger('year')->index();
            $table->unsignedBigInteger('agency_id')->nullable()->index();
            $table->enum('condition', ['B', 'KB', 'RB'])->default('B');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
