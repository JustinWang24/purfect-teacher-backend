<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\Models\Acl\Role;
use Illuminate\Support\Facades\Hash;
use App\Dao\Users\UserDao;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;

class CreateTeacherAndStudentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 创建 已认证老师
        $dao = new UserDao();
        if(!$dao->getUserByMobile('18601216001')){
            $su = [
                'mobile'=>'18601216001',
                'uuid'=>Uuid::uuid4()->toString(),
                'password'=>Hash::make('ac59075b964b0715'),
                'status'=>User::STATUS_VERIFIED,
                'type'=>Role::TEACHER,
                'mobile_verified_at'=>Carbon::now(),
            ];
            User::create($su);
        }

        // 创建 已认证学生
        if(!$dao->getUserByMobile('18601216002')){
            $operator = [
                'mobile'=>'18601216002',
                'uuid'=>Uuid::uuid4()->toString(),
                'password'=>Hash::make('ac59075b964b0715'),
                'status'=>User::STATUS_VERIFIED,
                'type'=>Role::VERIFIED_USER_STUDENT,
                'mobile_verified_at'=>Carbon::now(),
            ];
            User::create($operator);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
           Schema::dropIfExists('teacher_and_student');
    }
}
