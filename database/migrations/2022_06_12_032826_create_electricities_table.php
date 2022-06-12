<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElectricitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('electricities', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('electricity_rate_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->integer('amount')->unsigned();
            $table->integer('bonus')->unsigned();
            $table->foreign('electricity_rate_id')->references('id')->on('electricity_rates')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('sysusers')
                ->onUpdate('cascade')
                ->onDelete('cascade');
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
        Schema::dropIfExists('electricities');
    }
}
