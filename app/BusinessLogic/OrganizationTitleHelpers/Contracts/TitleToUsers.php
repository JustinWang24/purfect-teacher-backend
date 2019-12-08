<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 6/12/19
 * Time: 2:59 PM
 */

namespace App\BusinessLogic\OrganizationTitleHelpers\Contracts;
use App\User;

interface TitleToUsers
{
    /**
     * @return User[]
     */
    public function getUsers();
}