<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyCourseArrangements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_arrangements', function (Blueprint $table) {
            $table->unsignedInteger('building_id')->comment('教学楼id');
            $table->string('building_name',40)->comment('教学楼名称');
            $table->unsignedInteger('classroom_id')->comment('教室id');
            $table->string('classroom_name',40)->comment('教室名称');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_arrangements', function (Blueprint $table) {
            $table->dropColumn('building_id');
            $table->dropColumn('building_name');
            $table->dropColumn('classroom_id');
            $table->dropColumn('classroom_name');
        });
    }
}
