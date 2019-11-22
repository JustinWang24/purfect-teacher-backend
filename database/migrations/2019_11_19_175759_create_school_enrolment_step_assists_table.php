<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolEnrolmentStepAssistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_enrolment_step_assists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('school_enrolment_step_id')->comment('迎新步骤ID');
            $table->integer('user_id')->comment('协助人');
        });
        DB::statement(" ALTER TABLE school_enrolment_step_assists comment '迎新步骤协助人关联表' ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('school_enrolment_step_assists');
    }
}
