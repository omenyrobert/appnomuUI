<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserManagementToUserAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_accounts', function (Blueprint $table) {
            $table->boolean('suspended')->default('false');
            $table->boolean('blacklisted')->default('false');
            $table->timestamp('suspended_at')->nullable();
            $table->timestamp('unsuspended_at')->nullable();
            $table->timestamp('blacklisted_at')->nullable();
            $table->timestamp('unblacklisted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_accounts', function (Blueprint $table) {
            //
        });
    }
}
