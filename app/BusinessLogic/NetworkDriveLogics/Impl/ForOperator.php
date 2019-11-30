<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 17/11/19
 * Time: 12:53 PM
 */

namespace App\BusinessLogic\NetworkDriveLogics\Impl;
use App\BusinessLogic\NetworkDriveLogics\Contracts\ICategoryLogic;
use App\Dao\NetworkDisk\CategoryDao;
use App\Models\NetworkDisk\Category;
use App\User;

class ForOperator implements ICategoryLogic
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
        $dao = new CategoryDao();
        return $dao->getAllSchoolRootCategory();
    }

    public function getData()
    {
        if(is_null($this->uuid)){
            return [
                'category'=>[
                    'uuid'=>0,
                    'name'=>'网盘根目录',
                    'type'=>Category::TYPE_ROOT,
                    'created_at'=>'2019-11-19',
                    'children'=>$this->getAllSchoolRootCategory(),
                    'parent'=>'',
                    'files'=>[],
                ]
            ];
        }
        else{
            return $this->getCategoryByUuid();
        }
    }

}