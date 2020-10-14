<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParkingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parkings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type_vehicle_id');
            $table->unsignedBigInteger('rate_id');
            $table->unsignedBigInteger('slot_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('vehicle_id');
            $table->dateTime('in_time');
            $table->dateTime('out_time')->nullable();
            $table->time('total_time')->nullable();
            $table->decimal('earned_amount', 8, 2 )->default(0)->nullable();
            $table->boolean('paid_status')->default(0);
            $table->timestamps();

            $table->foreign('type_vehicle_id')->references('id')->on('type_vehicles');
            $table->foreign('rate_id')->references('id')->on('rates');
            $table->foreign('slot_id')->references('id')->on('slots');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('vehicle_id')->references('id')->on('vehicles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parkings');
    }
}
