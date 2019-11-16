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
        if(!$this->uuid){
            $category = $this->user->networkDiskRoot;
            if(!$category){
                // 说明学生还没有根目录, 那么就创建一个新的根目录给他
                // 先找到学校的根目录
                $schoolRoot = $categoriesDao->getSchoolRootCategory($this->user->getSchoolId());
                $category = $categoriesDao->createCategory(
                    '我的文档',
                    Category::TYPE_USER_ROOT,
                    $this->user->id,
                    $this->user->getSchoolId(),
                    $schoolRoot
                );
            }
        }else{
            $category = $categoriesDao->getCateInfoByUuId($this->uuid);
        }
        return $category ? [
            'category'=>[
                'uuid'=>$category->uuid,
                'name'=>$category->name,
                'type'=>$category->type,
                'created_at'=>$category->created_at,
                'children'=>$category->children,
                'parent'=>$category->parent,
                'files'=>$category->files,
            ]
        ] : null;
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