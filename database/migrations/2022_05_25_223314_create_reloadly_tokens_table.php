<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReloadlyTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reloadly_tokens', function (Blueprint $table) {
            $table->id();
            $table->enum('type',['topup','utility']);
            $table->string('status');
            $table->longText('token');
            $table->integer('life');
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
        Schema::dropIfExists('reloadly_tokens');
    }
}
