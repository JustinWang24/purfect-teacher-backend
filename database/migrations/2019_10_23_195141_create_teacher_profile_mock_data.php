<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\User;
use Faker\Factory;
use App\Dao\Teachers\TeacherProfileDao;
use Ramsey\Uuid\Uuid;

class CreateTeacherProfileMockData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $gradeUsers = DB::table('grade_users')->where('user_type',User::TYPE_EMPLOYEE)
            ->take(100)->get()->toArray();

        $faker = Factory::create();
        $dao = new TeacherProfileDao();

        foreach ($gradeUsers as $gradeUser) {
            $name = $faker->name;
            $data = [
                'uuid' => Uuid::uuid4()->toString(),
                'teacher_id' => $gradeUser->user_id,
                'school_id' => $gradeUser->school_id,
                'name'=>strlen($name) > 20 ? substr($name,0, 20) : $name,
                'gender'=>1,
                'country'=>'中国',
                'state'=>'北京',
                'city'=>'北京',
                'postcode'=>100046,
                'address_line'=>$faker->address,
                'address_in_school'=>$faker->address,
                'device'=>$faker->word,
                'birthday'=>$faker->date('Y-m-d'),
                'avatar'=>$faker->imageUrl(),
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

    }
}
