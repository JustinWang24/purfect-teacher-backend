<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Acl\Permission;
use App\Models\Acl\Role;

class CreateBasicPermissions1 extends Migration
{
    /**
     * 创建所有的基础数据权限
     *
     * @return void
     */
    public function up()
    {
        // 首先给超级管理员角色赋予所有的权限
        $roleDao = new \App\Dao\Users\RoleDao();
        $su = $roleDao->getBySlug(Role::SUPER_ADMIN_SLUG);

        $actions = config('acl.actions');
        $slug = [];
        foreach ($actions as $action) {
            $slug[$action] = true;
        }
        $permissions = config('acl.permissions');
        foreach ($permissions as $permissionName) {
            $permission = new Permission();
            $thePermission = $permission->create([
                'name'=>$permissionName,
                'slug'=>$slug,
                'description'=> trans('permission.'.$permissionName).'的管理权限集合'
            ]);
            $su->assignPermission($thePermission);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $roleDao = new \App\Dao\Users\RoleDao();
        $su = $roleDao->getBySlug(Role::SUPER_ADMIN_SLUG);
        $su->revokeAllPermissions();

        $ps = Permission::all();
        foreach ($ps as $p) {
            $p->delete();
        }
    }
}
