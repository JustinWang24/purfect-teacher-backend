<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Acl\Role;

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
                $table->string('name')->unique();
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        $role = new Role();
        $role->name = '超级管理员';
        $role->slug = 'su';
        $role->description = '系统超级管理员';
        $role->save();

        $role = new Role();
        $role->name = '校方管理员';
        $role->slug = 'school_admin';
        $role->description = '学校方系统管理员';
        $role->save();

        $role = new Role();
        $role->name = '运营人员';
        $role->slug = 'operator';
        $role->description = '日常操作人员';
        $role->save();

        $role = new Role();
        $role->name = '未注册用户';
        $role->slug = 'visitor';
        $role->description = '未注册用户';
        $role->save();

        $role = new Role();
        $role->name = '已注册用户';
        $role->slug = 'registered';
        $role->description = '已注册用户';
        $role->save();

        $role = new Role();
        $role->name = '已认证的学生';
        $role->slug = 'verified_student';
        $role->description = '已认证的学生用户';
        $role->save();

        $role = new Role();
        $role->name = '班长';
        $role->slug = 'verified_class_leader';
        $role->description = '已认证的学生用户(班长)';
        $role->save();

        $role = new Role();
        $role->name = '团支书';
        $role->slug = 'verified_class_secretary';
        $role->description = '已认证的学生用户(团支书)';
        $role->save();

        $role = new Role();
        $role->name = '教师';
        $role->slug = 'teacher';
        $role->description = '已认证的教师用户';
        $role->save();

        $role = new Role();
        $role->name = '教工';
        $role->slug = 'school_employee';
        $role->description = '已认证的教工用户';
        $role->save();

        $role = new Role();
        $role->name = '管理员';
        $role->slug = 'school_manager';
        $role->description = '已认证的管理员用户';
        $role->save();

        $role = new Role();
        $role->name = '已认证的企业';
        $role->slug = 'company';
        $role->description = '已认证的企业';
        $role->save();

        $role = new Role();
        $role->name = '配送员';
        $role->slug = 'delivery';
        $role->description = '已认证的配送员';
        $role->save();

        $role = new Role();
        $role->name = '校内商家';
        $role->slug = 'business_inner';
        $role->description = '已认证的校内商家';
        $role->save();

        $role = new Role();
        $role->name = '校外商家';
        $role->slug = 'business_outer';
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
