<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AppendWinterSummerStartEndDateInConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('school_configurations', function (Blueprint $table) {
            $table->date('summer_start_date')->nullable()->comment('夏季开始日期');
            $table->date('winter_start_date')->nullable()->comment('冬季开始日期');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('school_configurations', function (Blueprint $table) {
            $table->dropColumn('summer_start_date');
            $table->dropColumn('winter_start_date');
        });
    }
}
