<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 15/11/19
 * Time: 9:09 PM
 */

namespace App\BusinessLogic\NetworkDriveLogics\Impl;


use App\BusinessLogic\NetworkDriveLogics\Contracts\ICategoryLogic;
use App\Dao\NetworkDisk\CategoryDao;
use App\Models\NetworkDisk\Category;
use App\User;

class ForSchoolManager implements ICategoryLogic
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
        if(is_null($this->uuid)){
            try{
                $dao = new CategoryDao();
                $category = $dao->getSchoolRootCategory($this->user->getSchoolId());
                if($category){
                    $this->uuid = $category->uuid;
                }
            }catch (\Exception $exception){
                return [];
            }
        }
        return $this->getCategoryByUuid();
    }
}