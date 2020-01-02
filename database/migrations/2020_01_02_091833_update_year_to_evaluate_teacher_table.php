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
            $table->dropColumn('group_id');
            $table->float('score',6,2)->comment('平均数')->change();
            $table->smallInteger('num')->default(0)->comment('评价的人数');
        });

        Schema::table('evaluate_students', function (Blueprint $table) {
            //
            $table->integer('grade_id')->comment('班级ID');
        });

        Schema::table('evaluates', function (Blueprint $table) {
            $table->smallInteger('type')->default(\App\Models\Evaluate\Evaluate::TYPE_TEACHER)->comment('1给老师评价, 2给学生评价')->change();
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
            $table->integer('group_id')->comment('教研组ID');
            $table->tinyInteger('score')->default(0)->comment('平均数');
            $table->dropColumn('num');
        });

        Schema::table('evaluate_students', function (Blueprint $table) {
            //
            $table->dropColumn('grade_id');
        });

        Schema::table('evaluates', function (Blueprint $table) {
            $table->smallInteger('type')->default(\App\Models\Evaluate\Evaluate::TYPE_TEACHER)->comment('1给老师评价, 2给学生评价')->change();
        });

    }
}
