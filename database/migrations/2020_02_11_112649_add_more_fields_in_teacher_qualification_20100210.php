<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreFieldsInTeacherQualification20100210 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teacher_qualifications', function (Blueprint $table) {
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('media_id')->nullable()->comment('关联的文件ID，可以不填写');
            $table->string('file_name')->comment('证明材料的文件名');
            $table->unsignedSmallInteger('status')
                ->default(\App\Models\Teachers\TeacherQualification::STATUS_PENDING)
                ->comment('业绩是否被学校采信');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::table('teacher_qualifications', function (Blueprint $table) {
            $table->dropColumn('school_id');
            $table->dropColumn('media_id');
            $table->dropColumn('file_name');
            $table->dropColumn('status');
        });
    }
}
