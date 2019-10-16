<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\Models\Acl\Role;
use App\Dao\Users\UserDao;

class AssignTeacherAndStudentRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $userDao = new UserDao();
        // 给老师赋予角色
        $su = $userDao->getUserByMobile('18601216001');

        if($su){
            $userDao->assignRoleToUser($su, Role::TEACHER, true);
        }

        // 给学生赋予角色
        $op = $userDao->getUserByMobile('18601216002');

        if($op){
            $userDao->assignRoleToUser($op, Role::VERIFIED_USER_STUDENT_SLUG, true);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
