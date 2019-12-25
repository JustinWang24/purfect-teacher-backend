<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 24/12/19
 * Time: 3:29 AM
 */

namespace App\BusinessLogic\OrganizationTitleHelpers\Impl;


use App\BusinessLogic\OrganizationTitleHelpers\Contracts\TitleToUsers;
use App\Dao\Schools\YearManagerDao;
use App\User;

class GradeAdviser implements TitleToUsers
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUsers()
    {
        $dao = new YearManagerDao();

        $advisers = $dao->getBySchool($this->user->getSchoolId());

        $users = [];

        foreach ($advisers as $adviser) {
            $users[] = $adviser->user;
        }
        return $users;
    }
}