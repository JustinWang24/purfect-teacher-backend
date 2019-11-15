<?php


namespace App\Dao\NetworkDisk;

use App\Models\NetworkDisk\Category;
use Illuminate\Support\Facades\DB;

class CategoryDao
{

    /**
     * 获取用户指定目录
     * @param $userId
     * @param $type
     * @return mixed
     */
    public function getMyCategoryByUserId($userId, $type)
    {
        $where =['owner_id' => $userId, 'type' => $type];
        return Category::where($where)->first();
    }


    /**
     * 创建
     * @param $data
     * @return mixed
     */
    public function create($data) {
        return Category::create($data);
    }


    /**
     * 判断该文件目录是否存在
     * @param $name
     * @param $ownerId
     * @param $parentId
     * @return bool
     */
    public function isExist($name,$ownerId,$parentId) {
        $map = ['name'=>$name,'owner_id'=>$ownerId,'parent_id'=>$parentId];
        $result = Category::where($map)->first();
        if(is_null($result)) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * 编辑
     * @param $uuid
     * @param $data
     * @return mixed
     */
    public function update($uuid,$data) {
        return Category::where('uuid',$uuid)->update($data);
    }


    /**
     * 根据uuid获取目录详情
     * @param $uuid
     * @return mixed
     */
    public function getCateInfoByUuId($uuid) {
        return Category::GetByUuid($uuid);
    }


    /**
     * 根据ID获取目录详情
     * @param $id
     * @return mixed
     */
    public function getCateInfoById($id) {
        return Category::where('id',$id)->first();
    }


    /**
     * 根据用户ID和文件的父级ID获取列表
     * @param $ownerId
     * @param $parentId
     * @return mixed
     */
    public function getCateByOwnerIdAndParentId($ownerId, $parentId) {

        $map = ['owner_id'=>$ownerId, 'parent_id'=>$parentId];
        return Category::where($map)->orderBy('id')->get();
    }


    /**
     * 删除当前目录下所有的子目录和文件
     * @param Category $category
     * @return bool
     * @throws \Exception'
     */
    public function deleteCategory(Category $category){

        foreach ($category->children as $child) {
            /**
             * @var Category $child
             */
            $this->deleteCategory($child);
        }

        try{
            $dao = new MediaDao();
            $dao->deleteMediasByCategory($category);
            $category->delete();
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage() . ' Category:'. $category->id);
        }

        return true;
    }




}
