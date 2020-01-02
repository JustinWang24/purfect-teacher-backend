<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateYearToEvaluateTeacherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evaluate_teachers', function (Blueprint $table) {
            //
            $table->smallInteger('year')->comment('学年')->change();
        });

        Schema::table('evaluate_students', function (Blueprint $table) {
            //
            $table->integer('grade_id')->comment('班级ID');
        });

        Schema::table('evaluate_teachers', function (Blueprint $table) {
            //
            $table->dropColumn('group_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('evaluate_teachers', function (Blueprint $table) {
            //
            $table->smallInteger('year')->comment('学年')->change();
        });

        Schema::table('evaluate_students', function (Blueprint $table) {
            //
            $table->dropColumn('grade_id');
        });

        Schema::table('evaluate_teachers', function (Blueprint $table) {
            //
            $table->integer('group_id')->comment('教研组ID');
        });
    }
}
