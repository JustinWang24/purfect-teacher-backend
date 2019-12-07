<?php
/**
 * 获取校长
 */

namespace App\BusinessLogic\OrganizationTitleHelpers\Impl;
use App\BusinessLogic\OrganizationTitleHelpers\Contracts\TitleToUsers;
use App\Dao\Users\UserOrganizationDao;

class SchoolPrincipal implements TitleToUsers
{
    private $schoolId;

    public function __construct($schoolId)
    {
        $this->schoolId = $schoolId;
    }

    public function getUsers()
    {
        $userOrgDao = new UserOrganizationDao();
        $p = $userOrgDao->getPrinciple($this->schoolId);
        $users = [];
        if($p){
            $users[] = $p;
        }
        return $users;
    }
}