<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUserloanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('userloans', function (Blueprint $table) {
            $table->integer('payment_amount')->nullable();
            $table->integer('payment_period')->nullable();
            $table->date('approved_at')->nullable();
            $table->date('due_date')->nullable();
            $table->enum('status',['Paid','Approved','Requested','Over Due','Denied','Warning','On Hold','Cancelled']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('userloans', function (Blueprint $table) {
            $table->dropColumn('payment_amount');
            $table->dropColumn('payment_period');
            $table->dropColumn('approved_at');
            $table->dropColumn('due_date');
            $table->dropColumn('status');
        });
    }
}
