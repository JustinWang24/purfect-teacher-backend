<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Faker\Factory;
use Ramsey\Uuid\Uuid;
use App\User;
use App\Models\Acl\Role;
use App\Models\Users\GradeUser;
use App\Models\Students\StudentProfile;
use Illuminate\Support\Facades\DB;

class GenerateStudentsProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $faker = Factory::create();
        $teachersId = GradeUser::select(['user_id'])
            ->where('user_type',Role::VERIFIED_USER_STUDENT)
            ->get();

        foreach ($teachersId as $gradeUser) {
            /**
             * @var GradeUser $gradeUser
             */
            $rand = random_int(0, 1);
            $data = [
                'uuid' => Uuid::uuid4()->toString(),
                'user_id' => $gradeUser->user_id,
                'device' => random_int(1000000, 9999999), // 教师任职的学校
                'year' => 2019, // 教师编号
                'serial_number' => random_int(1000000, 9999999), // 录取编号
                'gender' => $rand,
                'country' => 'cn',
                'state' => '广东',
                'city' => '广州',
                'postcode' => '100010',
                'area' => '白云山', // 地区名称
                'address_line' => '胜利路 ' . random_int(1000, 9999) . ' 号',
                'address_in_school' => $faker->address,
                'student_number' => random_int(1000000, 9999999), // 考生号
                'license_number' => random_int(1000000, 9999999), // 准考证号
                'id_number' => random_int(10000000, 99999999), // 身份证号
                'birthday',
                'avatar' => '',
                'political_code', // 政治面貌代码
                'political_name', // 政治面貌名称
                'nation_code', // 民族代码
                'nation_name', // 民族名称
            ];
            StudentProfile::create($data);
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
            DB::table('student_profiles')->where('id','>',0)->delete();
        }
    }
}
