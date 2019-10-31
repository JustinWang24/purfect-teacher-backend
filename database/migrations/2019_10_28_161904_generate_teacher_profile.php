<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Faker\Factory;
use Ramsey\Uuid\Uuid;
use App\User;
use App\Models\Acl\Role;
use App\Models\Users\GradeUser;

class GenerateTeacherProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(env('APP_ENV') === 'production'){
            return ;
        }

        DB::table('grade_users')->where('user_type',1)->update(['user_type'=>Role::VERIFIED_USER_STUDENT]);
        DB::table('grade_users')->where('user_type',2)->update(['user_type'=>Role::TEACHER]);

        $faker = Factory::create();
        $teachersId = GradeUser::select(['user_id','school_id','department_id'])
            ->where('user_type',Role::TEACHER)
            ->get();

        $dao = new \App\Dao\Teachers\TeacherProfileDao();

        foreach ($teachersId as $gradeUser) {
            /**
             * @var GradeUser $gradeUser
             */
            $rand = random_int(0,1);
            $data = [
                'uuid'=>Uuid::uuid4()->toString(),
                'user_id'=>$gradeUser->user_id,
                'school_id'=>$gradeUser->school_id, // 教师任职的学校
                'serial_number'=>$faker->randomDigit, // 教师编号
                'group_name'=>$gradeUser->department->name, // 所在部门: 基础教学部
                'gender'=>$rand,
                'title'=>$rand ? '教授' : '讲师', // 教师职称: 教授, 讲师
                'id_number'=>$faker->randomDigit, // 身份证号
                'political_code',//政治面貌代码
                'political_name',//政治面貌名称
                'nation_code',//民族代码
                'nation_name',//民族名称
                'education',//学历
                'degree',//学位
                'birthday',
                'joined_at', // 入职日期
                'avatar'=>'',
            ];
            $dao->createProfile($data);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(env('APP_ENV') === 'local'){
            DB::table('teacher_profiles')->where('id','>',0)->delete();
        }
    }
}
