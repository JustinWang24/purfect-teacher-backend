<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 6/12/19
 * Time: 4:12 PM
 */

namespace App\BusinessLogic\OrganizationTitleHelpers\Impl;
use App\Dao\Users\UserOrganizationDao;

class SchoolCoordinator
{
    private $schoolId;

    public function __construct($schoolId)
    {
        $this->schoolId = $schoolId;
    }

    public function getUsers()
    {
        $userOrgDao = new UserOrganizationDao();
        $p = $userOrgDao->getCoordinator($this->schoolId);
        $users = [];
        if($p){
            $users[] = $p;
        }
        return $users;
    }
}