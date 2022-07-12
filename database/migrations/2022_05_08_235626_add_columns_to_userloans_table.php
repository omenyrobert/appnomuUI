<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUserloansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('userloans', function (Blueprint $table) {
            $table->bigInteger('declined_by')->unsigned()->nullable();
            $table->bigInteger('held_by')->unsigned()->nullable();
            $table->bigInteger('cancelled_by')->unsigned()->nullable();
            $table->timestamp('declined_at')->nullable();
            $table->timestamp('held_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('decline_reason')->nullable();
            $table->string('hold_reason')->nullable();
            $table->string('cancel_reason')->nullable();
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
            $table->dropColumn('');
        });
    }
}
