<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 5/11/19
 * Time: 12:56 PM
 */

namespace App\BusinessLogic\QuickSearch\Impl;
use App\Dao\Users\GradeUserDao;
use App\Models\Acl\Role;

class EmployeeQuickSearchLogic extends AbstractQuickSearchLogic
{
    public function getUsers()
    {
        $dao = new GradeUserDao();
        return $dao->getUsersWithNameLike($this->queryString, $this->schoolId, [Role::TEACHER,Role::EMPLOYEE]);
    }

    /**
     * 对教职工的搜索, 不需要任何的其他的 facility
     * @return array|\Illuminate\Support\Collection
     */
    public function getFacilities()
    {
        return [];
    }

    public function getNextAction($facility)
    {
        return '';
    }
}