<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Acl\Role;
use Illuminate\Support\Facades\DB;

class CreateRolesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('roles')){
            Schema::create('roles', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->unique()->comment('角色名称');
                $table->string('slug')->unique()->comment('角色身份');
                $table->text('description')->nullable()->comment('角色描述');
                $table->timestamps();
            });

            DB::statement(" ALTER TABLE roles comment '角色表' ");
        }

        $role = new Role();
        $role->name = '超级管理员';
        $role->slug = Role::SUPER_ADMIN_SLUG;
        $role->description = '系统超级管理员';
        $role->save();

        $role = new Role();
        $role->name = '校方管理员';
        $role->slug = Role::ADMINISTRATOR_SLUG;
        $role->description = '学校方系统管理员';
        $role->save();

        $role = new Role();
        $role->name = '运营人员';
        $role->slug = Role::OPERATOR_SLUG;
        $role->description = '日常操作人员';
        $role->save();

        $role = new Role();
        $role->name = '未注册用户';
        $role->slug = Role::VISITOR_SLUG;
        $role->description = '未注册用户';
        $role->save();

        $role = new Role();
        $role->name = '已注册用户';
        $role->slug = Role::REGISTERED_USER_SLUG;
        $role->description = '已注册用户';
        $role->save();

        $role = new Role();
        $role->name = '已认证的学生';
        $role->slug = Role::VERIFIED_USER_STUDENT_SLUG;
        $role->description = '已认证的学生用户';
        $role->save();

        $role = new Role();
        $role->name = '班长';
        $role->slug = Role::VERIFIED_USER_CLASS_LEADER_SLUG;
        $role->description = '已认证的学生用户(班长)';
        $role->save();

        $role = new Role();
        $role->name = '团支书';
        $role->slug = Role::VERIFIED_USER_CLASS_SECRETARY_SLUG;
        $role->description = '已认证的学生用户(团支书)';
        $role->save();

        $role = new Role();
        $role->name = '教师';
        $role->slug = Role::TEACHER_SLUG;
        $role->description = '已认证的教师用户';
        $role->save();

        $role = new Role();
        $role->name = '教工';
        $role->slug = Role::EMPLOYEE_SLUG;
        $role->description = '已认证的教工用户';
        $role->save();

        $role = new Role();
        $role->name = '管理员';
        $role->slug = Role::SCHOOL_MANAGER_SLUG;
        $role->description = '已认证的管理员用户';
        $role->save();

        $role = new Role();
        $role->name = '已认证的企业';
        $role->slug = Role::COMPANY_SLUG;
        $role->description = '已认证的企业';
        $role->save();

        $role = new Role();
        $role->name = '配送员';
        $role->slug = Role::DELIVERY_SLUG;
        $role->description = '已认证的配送员';
        $role->save();

        $role = new Role();
        $role->name = '校内商家';
        $role->slug = Role::BUSINESS_INNER_SLUG;
        $role->description = '已认证的校内商家';
        $role->save();

        $role = new Role();
        $role->name = '校外商家';
        $role->slug = Role::BUSINESS_OUTER_SLUG;
        $role->description = '已认证的校外商家';
        $role->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('roles');
    }

}
