<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 16/11/19
 * Time: 11:04 PM
 */

namespace App\BusinessLogic\NetworkDriveLogics\Impl;
use App\BusinessLogic\NetworkDriveLogics\Contracts\ICategoryLogic;
use App\User;
use App\Dao\NetworkDisk\CategoryDao;
use App\Models\NetworkDisk\Category;

class ForTeacher implements ICategoryLogic
{
    use CategoryByUuid;
    private $uuid;
    private $user;

    public function __construct($uuid, User $user)
    {
        $this->uuid = $uuid;
        $this->user = $user;
    }

    public function getAllSchoolRootCategory()
    {
        return 0;
    }

    public function getData()
    {
        return $this->getCategoryByUuid();
    }
}