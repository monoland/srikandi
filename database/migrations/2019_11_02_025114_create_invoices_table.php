<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('service_id')->unique();
            $table->unsignedBigInteger('vehicle_id')->index();
            $table->string('police_id')->index();
            $table->unsignedBigInteger('agency_id')->index();
            $table->unsignedBigInteger('garage_id')->nullable()->index();
            $table->string('periode')->index();
            $table->string('refsinv')->unique();
            $table->date('dateinv')->index();
            $table->double('subtotal', 8, 2)->default(0);
            $table->double('discount', 8, 2)->default(0);
            $table->double('tax', 8, 2)->default(0);
            $table->double('total', 8, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('invoice_item', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('invoice_id')->index();
            $table->unsignedBigInteger('item_id')->index();
            $table->string('name')->index();
            $table->string('unit')->nullable();
            $table->unsignedSmallInteger('qty')->default(0);
            $table->double('price', 8, 2)->default(0);
            $table->double('amount', 8, 2)->default(0);
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
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('invoice_item');
    }
}
