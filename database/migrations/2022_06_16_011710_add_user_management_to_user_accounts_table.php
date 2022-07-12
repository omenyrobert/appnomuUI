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
        Schema::table('user_account', function (Blueprint $table) {
            $table->enum('status',['active','inactive','suspended','blacklisted'])->default('inactive');
            $table->string('suspend_reason')->nullable();
            $table->timestamp('suspended_at')->nullable();
            $table->timestamp('unsuspended_at')->nullable();
            $table->string('unsuspend_reason')->nullable();
            $table->timestamp('blacklisted_at')->nullable();
            $table->string('blacklist_reason')->nullable();
            $table->timestamp('unblacklisted_at')->nullable();
            $table->string('unblacklist_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_account', function (Blueprint $table) {
            //
        });
    }
}
