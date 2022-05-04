<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeadteacherStudentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('headteacher_student', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('headteacher_id')->unsigned();
            $table->bigInteger('student_id')->unsigned();
            $table->string('student_admission_num');
            $table->foreign('headteacher_id')->references('id')->on('headteachers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('student_id')->references('id')->on('students')
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
        Schema::dropIfExists('headteacher_student');
    }
}
