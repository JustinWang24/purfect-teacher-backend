<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWeekIndexToSchoolCalendarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('school_calendars', function (Blueprint $table) {
            //
            $table->tinyInteger('week_idx')->comment('第几学周');
            $table->tinyInteger('term')->default(1)->comment('学期 1:上学期 2:下学期');
            $table->mediumInteger('year')->comment('学年');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('school_calendars', function (Blueprint $table) {
            //
            $table->dropColumn('week_idx');
            $table->dropColumn('term');
            $table->dropColumn('year');
        });
    }
}
