<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Acl\Role;
use App\Dao\Users\UserDao;

class CreateSchoolAdminRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $userDao = new UserDao();
        // 给学校管理员赋予角色
        $su = $userDao->getUserByMobile('1000006');

        if($su){
            $userDao->assignRoleToUser($su, Role::ADMINISTRATOR, true);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('school_admin_role');
    }
}
