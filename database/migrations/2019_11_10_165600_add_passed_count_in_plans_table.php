<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPassedCountInPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recruitment_plans', function (Blueprint $table) {
            $table->unsignedInteger('passed_count')->after('applied_count')->default(0)->comment('通过报名审核的人数');
            $table->unsignedBigInteger('enrol_manager')->after('manager_id')->default(0)->comment('负责审核学生入学资格的人');
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
            $table->dropColumn('passed_count');
            $table->dropColumn('enrol_manager');
        });
    }
}
