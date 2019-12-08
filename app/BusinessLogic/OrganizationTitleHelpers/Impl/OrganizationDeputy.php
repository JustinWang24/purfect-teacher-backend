<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 7/12/19
 * Time: 2:30 PM
 */

namespace App\BusinessLogic\OrganizationTitleHelpers\Impl;
use App\BusinessLogic\OrganizationTitleHelpers\Contracts\TitleToUsers;
use App\User;
use App\Dao\Users\UserOrganizationDao;

class OrganizationDeputy implements TitleToUsers
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUsers()
    {
        $users = [];
        if($this->user->isTeacher()){
            // Todo: 临时逻辑, 以后可能会修正. 对于老师来讲, 部门付经理也是系主任
            $obj = new DepartmentManager($this->user);
            $users = $obj->getUsers();
        }
        elseif ($this->user->isEmployee()){
            // 获取职工所在的部门
            $dao = new UserOrganizationDao();
            $org = $dao->getUserOrganization($this->user->id, $this->user->getSchoolId());
            $ugs = $dao->getOrganizationDeputies($org->id);
            foreach ($ugs as $ug){
                $users[] = $ug->user;
            }
        }
        return $users;
    }
}