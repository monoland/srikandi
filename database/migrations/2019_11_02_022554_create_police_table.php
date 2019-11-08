<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoliceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('police', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name')->index();
            $table->string('color')->nullable();
            $table->date('taxdue')->nullable();
            $table->double('taxsum', 8, 2)->default(0);
            $table->unsignedBigInteger('vehicle_id')->index();
            $table->boolean('active')->default(false);
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
        Schema::dropIfExists('police');
    }
}
