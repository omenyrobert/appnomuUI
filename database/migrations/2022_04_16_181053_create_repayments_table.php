<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repayments', function (Blueprint $table) {
            $table->id();
            $table->enum('status',['Pending','Paid','Over Due','On Hold'])->default('pending');
            $table->date('due_date');
            $table->string('loan_id');
            $table->bigInteger('user_id')->unsigned();
            $table->integer('amount')->unsigned();
            $table->integer('amount_paid')->default(0);
            $table->string('repaymentable_type');
            $table->bigInteger('repaymentable_id')->unsigned();
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
        Schema::dropIfExists('repayments');
    }
}
