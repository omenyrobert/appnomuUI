<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_credentials', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('business_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('business_loan_id')->unsigned();
            $table->string('name');
            $table->string('location');
            $table->bigInteger('district_id')->unsigned();
            $table->string('license_photo',255)->nullable();
            $table->string('premises_photo',255)->nullable();
            $table->string('business_photo',255)->nullable();
            $table->foreign('district_id')->references('id')->on('districts')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('business_id')->references('id')->on('businesses')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('business_loan_id')->references('id')->on('business_loans')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('sysusers')
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
        Schema::dropIfExists('business_credentials');
    }
}
