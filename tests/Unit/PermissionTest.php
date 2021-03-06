<?php

namespace Tests\Unit;

use App\Dao\Users\RoleDao;
use App\Models\Acl\Permission;
use App\Models\Acl\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Dao\Users\UserDao;

class PermissionTest extends TestCase
{
    /**
     * 系统应该已经创建了超级管理员
     * @return void
     */
    public function testItCanGetUserRoles()
    {
        $dao = new UserDao();
        $su = $dao->getUserByMobile('18601216091');
        $this->assertNotNull($su, '系统应该已经创建了超级管理员');
        $roles = $dao->getUserRoles($su);
        $this->assertNotNull($roles, '超级管理员应该已经被赋予了响应的角色');
        foreach ($roles as $role) {
            $this->assertEquals(Role::SUPER_ADMIN_SLUG, $role, '超级管理员应该有正确的角色 ID');
        }
    }

    /**
     * 不能给运营人员用户赋予超级管理员角色
     */
    public function testItCanNotAssignAnyUserAsSuperAdmin(){
        $dao = new UserDao();
        $op = $dao->getUserByMobile('18510209803');
        $this->assertNotNull($op, '系统应该已经创建了默认的运营人员用户');
        $result = $dao->assignRoleToUser($op, Role::SUPER_ADMIN);
        $this->assertFalse($result, '不能给运营人员用户赋予超级管理员角色');
    }

    /**
     * 不能给运营人员用户赋予超级管理员角色
     */
    public function testItCanAssignAndRevokeOrdinaryRole(){
        $dao = new UserDao();
        $op = $dao->getUserByMobile('18510209803');
        $this->assertNotNull($op, '系统应该已经创建了默认的运营人员用户');
        $result = $dao->assignRoleToUser($op, Role::BUSINESS_INNER);

        $this->assertTrue($result, '能给运营人员用户赋予一般角色');
        $revoked = $dao->revokeRoleFromUser($op, Role::BUSINESS_INNER);
        $this->assertTrue($revoked, '能撤销运营人员用户的一般角色');
    }

    /**
     * 可以从配置中加载权限
     */
    public function testItCanGetAllActionsAndPermissionsFromConfig(){
        $actions = config('acl.actions');
        $permissions = config('acl.permissions');
        $this->assertIsArray($actions);
        $this->assertIsArray($permissions);
    }

    /**
     * 可以通过 dao 获得角色
     */
    public function testItCanGetRoleFromDao(){
        $su = $this->_getRoleBySlug(Role::SUPER_ADMIN_SLUG);
        $this->assertNotNull($su);
    }

    /**
     * 可以给角色赋予权限, 撤销权限
     */
    public function testItCanAssignPermissionToRole(){
        $permission = new Permission();

        $thePermission = $permission->create([
            'name'=>'post',
            'slug'=>[
                'create'=>true,
                'delete'=>false,
            ],
            'descriptions'=>'Manage posts'
        ]);

        $su = $this->_getRoleBySlug(Role::SUPER_ADMIN_SLUG);
        $assigned = $su->assignPermission($thePermission);

        $this->assertNotNull($assigned);

        // 取消 permission
        $revoked = $su->revokePermission($thePermission);
        $this->assertEquals(1,$revoked);

        // 删除 permission

        $assigned->delete($thePermission->id);
    }

    private function _getRoleBySlug($slug){
        $dao = new RoleDao();
        return $dao->getBySlug($slug);
    }
}
