<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 15/11/19
 * Time: 9:03 PM
 */

namespace App\BusinessLogic\NetworkDriveLogics\Impl;

use App\BusinessLogic\NetworkDriveLogics\Contracts\ICategoryLogic;
use App\Dao\NetworkDisk\CategoryDao;
use App\Models\NetworkDisk\Category;
use App\User;

class ForStudent implements ICategoryLogic
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
        return null;
    }

    public function getData()
    {
        return $this->getCategoryByUuid();
    }
}