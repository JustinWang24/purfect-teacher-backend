<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreFieldsInTeacherQualificationsYue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teacher_qualifications', function (Blueprint $table) {
            $table->unsignedSmallInteger('type')->nullable()->comment('材料类型:论文, 课题， 荣誉，教学，技能大赛');
            $table->unsignedMediumInteger('year')->nullable()->comment('获取的年度');
            $table->unsignedBigInteger('uploaded_by')->nullable()->comment('由谁提交的');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teacher_qualifications', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('year');
            $table->dropColumn('uploaded_by');
        });
    }
}
