<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 6/12/19
 * Time: 3:13 PM
 */

namespace App\BusinessLogic\OrganizationTitleHelpers\Impl;
use App\BusinessLogic\OrganizationTitleHelpers\Contracts\TitleToUsers;
use App\Dao\Users\UserOrganizationDao;

class SchoolDeputy implements TitleToUsers
{
    private $schoolId;

    public function __construct($schoolId)
    {
        $this->schoolId = $schoolId;
    }

    public function getUsers()
    {
        $userOrgDao = new UserOrganizationDao();
        $uos = $userOrgDao->getDeputyPrinciples($this->schoolId);
        $users = [];
        foreach ($uos as $uo) {
            $users[] = $uo->user;
        }
        return $users;
    }
}