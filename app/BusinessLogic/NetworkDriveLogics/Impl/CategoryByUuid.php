<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 17/11/19
 * Time: 1:03 PM
 */

namespace App\BusinessLogic\NetworkDriveLogics\Impl;
use App\Dao\NetworkDisk\CategoryDao;
use App\Models\NetworkDisk\Category;

trait CategoryByUuid
{
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
                'parent'=>$category->isRootOf($this->user) ? null : $category->parentCategory,
                'files'=>$category->medias,
            ]
        ] : null;
    }
}