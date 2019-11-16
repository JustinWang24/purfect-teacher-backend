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
use App\User;

class ForStudent implements ICategoryLogic
{
    private $uuid;
    private $user;

    public function __construct($uuid, User $user)
    {
        $this->uuid = $uuid;
        $this->user = $user;
    }

    public function getCategoryByUuid()
    {
        $category = null;
        $categoriesDao = new CategoryDao();
        $category = $this->uuid ? $categoriesDao->getCateInfoByUuId($this->uuid) : $this->user->networkDiskRoot;
        return [
            'category'=>[
                'uuid'=>$category->uuid,
                'name'=>$category->name,
                'type'=>$category->type,
                'children'=>$category->children,
                'parent'=>$category->parent,
                'files'=>$category->files,
            ]
        ];
    }

    public function getAllSchoolRootCategory()
    {
        return null;
    }

    public function getData()
    {
        return $this->getCategoryByUuid($this->uuid);
    }
}