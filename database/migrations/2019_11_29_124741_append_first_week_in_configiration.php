<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AppendFirstWeekInConfigiration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('school_configurations', function(Blueprint $table){
            $table->date('first_day_term_1')->default('1980-03-01')->comment('第一学期的起始日期');
            $table->date('first_day_term_2')->default('1980-09-01')->comment('第2学期的结束日期');
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
            $table->dropColumn('first_day_term_1');
            $table->dropColumn('first_day_term_2');
        });
    }
}
