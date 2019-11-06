<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MajorAndRecruitmentPlansRefine extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 修复报名信息表的错误
        Schema::table('majors', function (Blueprint $table) {
            // 删除无用的字段
            if(Schema::hasColumn('majors','open')){
                $table->dropColumn('open');
            }
            if(Schema::hasColumn('majors','hot')){
                $table->dropColumn('hot');
            }
            if(Schema::hasColumn('majors','seats')){
                $table->dropColumn('seats');
            }
            if(Schema::hasColumn('majors','fee')){
                $table->dropColumn('fee');
            }

            // 添加专业的类型
            $table->unsignedSmallInteger('type')->default(\App\Models\Schools\Major::TYPE_UNION_FULL_DAY)
                ->comment('例如普通全日制、普通非全日制、校企联合办学全日制');
            // 专业优势
            $table->text('advantage_introduction')->nullable()->comment('专业优势');
            // 毕业前景
            $table->text('future')->nullable()->comment('毕业前景');
            // 资助政策
            $table->text('funding_policy')->nullable()->comment('资助政策');
        });

        // 添加专业的名师介绍: 解决方式为, 在教师的资料表格中, 增加代表为名师的字段
        Schema::table('teacher_profiles', function (Blueprint $table) {
            // 是否为名师
            $table->boolean('famous')->default(false)->comment('是否为名师');
        });

        Schema::table('student_profiles', function (Blueprint $table) {
            // 入学时, 生源的类型
            $table->unsignedSmallInteger('source')->default(false)->comment('入学时, 生源的类型');
        });

        // 在新创建的招生简章表中, 加入以下字段
        Schema::table('recruitment_plans', function (Blueprint $table) {
            // 招生对象
            $table->text('target_students')->after('description')->nullable()->comment('招生对象');
            // 报名条件
            $table->text('student_requirements')->after('description')->nullable()->comment('报名条件');
            // 录取方式
            $table->text('how_to_enrol')->after('description')->nullable()->comment('录取方式');

            // 删除无用的字段
            if(Schema::hasColumn('recruitment_plans','expired')){
                $table->dropColumn('expired');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('majors', function (Blueprint $table) {
            // 报名信息
            $table->dropColumn('type');
            $table->dropColumn('advantage_introduction');
            $table->dropColumn('future');
            $table->dropColumn('funding_policy');
        });
        Schema::table('teacher_profiles', function (Blueprint $table) {
            // 是否为名师
            $table->dropColumn('famous');
        });
        Schema::table('recruitment_plans', function (Blueprint $table) {
            // 是否为名师
            $table->dropColumn('target_students');
            $table->dropColumn('student_requirements');
            $table->dropColumn('how_to_enrol');
        });

        Schema::table('student_profiles', function (Blueprint $table) {
            // 入学时, 生源的类型
            $table->dropColumn('source');
        });
    }
}
