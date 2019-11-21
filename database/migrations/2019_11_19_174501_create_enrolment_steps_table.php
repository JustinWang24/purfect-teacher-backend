<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnrolmentStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enrolment_steps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',100)->comment('步骤名称');
        });
        DB::statement(" ALTER TABLE enrolment_steps comment '系统预设迎新步骤表' ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enrolment_steps');
    }
}
