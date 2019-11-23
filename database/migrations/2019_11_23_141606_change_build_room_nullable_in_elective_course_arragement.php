<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeBuildRoomNullableInElectiveCourseArragement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('apply_course_arrangements', function (Blueprint $table) {
            $table->unsignedInteger('building_id')->nullable()->change();
            $table->unsignedInteger('classroom_id')->nullable()->change();
            $table->string('building_name')->nullable()->change();
            $table->string('classroom_name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('apply_course_arrangements', function (Blueprint $table) {
            //
        });
    }
}
