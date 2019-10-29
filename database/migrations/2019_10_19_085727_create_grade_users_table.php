<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateGradeUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('grade_users')) {

                Schema::create('grade_users', function (Blueprint $table) {
                $table->integerIncrements('id');
                $table->unsignedBigInteger('user_id')->index();
                $table->smallInteger('user_type')->default(1)->comment('1:学生 2:老师 3:管理员 4:合作伙伴 5:运营商 6运营商管理员 100超级用户');
                // 用户关联的学校
                $table->unsignedInteger('school_id')->comment('学校ID');
                // 用户的校区, 如果为 0 表示学校无多个校区的情况
                $table->unsignedInteger('campus_id')->default(0)->comment('校区 0为无多个校区');
                // 用户的学院, 如果为 0 表示未知
                $table->unsignedInteger('institute_id')->default(0)->comment('用户的学院');
                // 用户的系, 如果为 0 表示未知
                $table->unsignedInteger('department_id')->default(0)->comment('用户所在系');
                // 用户的专业, 如果为 0 表示未知
                $table->unsignedInteger('major_id')->default(0)->comment('专业');
                // 用户的年级, 如果为 0 表示未知
                $table->unsignedInteger('grade_id')->default(0)->comment('年级');
                // 最后更新该校区的信息的用户ID
                $table->unsignedBigInteger('last_updated_by')->default(0)->comment('更新信息的用户id');
                $table->timestamps();
                $table->softDeletes();
            });

            DB::statement(" ALTER TABLE grade_users comment '用户班级关系表' ");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grade_users');
    }
}
