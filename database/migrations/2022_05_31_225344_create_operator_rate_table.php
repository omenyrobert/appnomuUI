<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperatorRateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operator_rate', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('operator_id')->unsigned();
            $table->bigInteger('rate_id')->unsigned();
            $table->enum('status',['Active','Inactive'])->default('Active');
            $table->foreign('operator_id')->references('id')->on('airtime_operators')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('rate_id')->references('id')->on('airtime_rates')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
        Schema::dropIfExists('operator_rate');
    }
}
