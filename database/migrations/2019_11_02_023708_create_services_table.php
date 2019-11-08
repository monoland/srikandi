<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vehicle_id')->index();
            $table->string('police_id')->index();
            $table->unsignedBigInteger('agency_id')->index();
            $table->unsignedBigInteger('garage_id')->nullable()->index();
            $table->string('periode')->index();
            $table->unsignedSmallInteger('year')->index();
            $table->text('notes')->nullable();
            $table->enum('status', [
                'drafted', 'dispositioned', 'submissioned', 'examined', 'approved', 'printed', 'invoiced', 'completed'
            ])->default('drafted')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->timestamps();
        });

        Schema::create('item_service', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('item_id')->index();
            $table->unsignedBigInteger('service_id')->index();
            $table->string('name')->index();
            $table->string('unit')->nullable();
            $table->unsignedSmallInteger('used')->default(0);
            $table->unsignedSmallInteger('blnc')->default(0);
            $table->boolean('aprv')->default(false);
            $table->boolean('exmn')->default(false);
            $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
        Schema::dropIfExists('item_service');
    }
}
