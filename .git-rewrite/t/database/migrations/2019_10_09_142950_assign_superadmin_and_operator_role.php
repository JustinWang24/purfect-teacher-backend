<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Dao\Users\UserDao;
use App\Models\Acl\Role;

class AssignSuperadminAndOperatorRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $userDao = new UserDao();
        // 给超级管理员赋予对应的角色
        $su = $userDao->getUserByMobile('18601216091');

        if($su){
            $userDao->assignRoleToUser($su, Role::SUPER_ADMIN, true);
        }

        // 给运营人员赋予对应的角色
        $op = $userDao->getUserByMobile('18510209803');

        if($op){
            $userDao->assignRoleToUser($op, Role::OPERATOR, true);
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
