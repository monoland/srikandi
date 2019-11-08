<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedSmallInteger('year')->index();
            $table->unsignedBigInteger('vehicle_id')->index();
            $table->double('maxi', 8, 2)->default(0);
            $table->double('warn', 8, 2)->default(0);
            $table->double('blnc', 8, 2)->default(0);
            $table->timestamps();

            $table->unique(['vehicle_id', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('balances');
    }
}
