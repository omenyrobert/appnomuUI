<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSomaLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('soma_loans', function (Blueprint $table) {
            $table->id();
            $table->string('SLN_id')->unique()->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('student_id')->unsigned();
            $table->bigInteger('loan_category_id')->unsigned();
            $table->bigInteger('account_id')->unsigned();
            $table->bigInteger('approved_by')->unsigned()->nullable();
            $table->bigInteger('declined_by')->unsigned()->nullable();
            $table->bigInteger('held_by')->unsigned()->nullable();
            $table->bigInteger('cancelled_by')->unsigned()->nullable();
            $table->float('interest_rate',8,2,true);
            $table->integer('interest')->unsigned()->nullable();
            $table->integer('principal')->unsigned()->nullable();
            $table->integer('payment_period')->unsigned()->nullable();
            $table->integer('installments')->unsigned()->nullable();
            $table->integer('payment_amount')->unsigned()->nullable();
            $table->integer('amount_paid')->unsigned()->default(0);
            $table->enum('status',['Paid','Approved','Requested','Over Due','Denied','Warning','On Hold','Cancelled'])->default('Requested');
            $table->date('due_date')->nullable();
            $table->date('approved_at')->nullable();
            $table->date('declined_at')->nullable();
            $table->date('held_at')->nullable();
            $table->date('cancelled_at')->nullable();
            $table->string('decline_reason')->nullable();
            $table->string('hold_reason')->nullable();
            $table->string('cancel_reason')->nullable();
            $table->foreign('user_id')->references('id')->on('sysusers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('student_id')->references('id')->on('students')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('loan_category_id')->references('id')->on('loanchart')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('account_id')->references('id')->on('user_account')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('approved_by')->references('id')->on('sysusers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('declined_by')->references('id')->on('sysusers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('cancelled_by')->references('id')->on('sysusers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('held_by')->references('id')->on('sysusers')
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
        Schema::dropIfExists('soma_loans');
    }
}
