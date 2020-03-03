<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEvaluateTeacherIdToEvaluateTeacherRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evaluate_teacher_records', function (Blueprint $table) {
            //
            $table->integer('evaluate_teacher_id')->comment('评教主表ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('evaluate_teacher_records', function (Blueprint $table) {
            //
            $table->dropColumn('evaluate_teacher_id');
        });
    }
}
