<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 15/11/19
 * Time: 9:09 PM
 */

namespace App\BusinessLogic\NetworkDriveLogics\Impl;


use App\BusinessLogic\NetworkDriveLogics\Contracts\ICategoryLogic;
use App\User;

class ForSchoolManager implements ICategoryLogic
{
    public function __construct($uuid, User $user)
    {
        $this->uuid = $uuid;
        $this->user = $user;
    }

    public function getCategoryByUuid()
    {
        // TODO: Implement getCategoryByUuid() method.
    }

    public function getAllSchoolRootCategory()
    {
        // TODO: Implement getAllSchoolRootCategory() method.
    }

    public function getData()
    {
        // TODO: Implement getData() method.
    }


}