<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AppendOpenDateInRecruitmentPlan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recruitment_plans', function (Blueprint $table) {
            $table->date('opening_date')->nullable()->comment('本次招生: 开学日期');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recruitment_plans', function (Blueprint $table) {
            $table->dropColumn('opening_date');
        });
    }
}
