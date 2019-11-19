<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolEnrolmentStepMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_enrolment_step_medias', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('school_enrolment_step_id')->comment('迎新步骤ID');
            $table->integer('media_id')->comment('媒体ID');
        });
        DB::statement(" ALTER TABLE school_enrolment_step_medias comment '迎新媒体关联表' ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('school_enrolment_step_medias');
    }
}
