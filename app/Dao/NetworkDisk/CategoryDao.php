<?php


namespace App\Dao\NetworkDisk;

use App\Dao\Schools\SchoolDao;
use App\Models\NetworkDisk\Category;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

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
     * 标准的创建根目录的方法
     * @param $name
     * @param $type
     * @param $userId
     * @param $schoolId
     * @param $parentCategory
     * @return Category|null
     */
    public function createCategory($name, $type, $userId, $schoolId, $parentCategory){
        $parentCategoryId = $parentCategory;
        if(is_object($parentCategory)){
            $parentCategoryId = $parentCategory->id;
        }
        try{
            return $this->create([
                'uuid'=>Uuid::uuid4()->toString(),
                'name'=>$name,
                'type'=>$type,
                'school_id'=>$schoolId,
                'owner_id'=>$userId,
                'parent_id'=>$parentCategoryId
            ]);
        }catch (\Exception $exception){
            // 处理创建 category 时的异常
            Log::critical('文件读写失败',['msg'=>$exception->getMessage(),'user'=>$userId,'school'=>$schoolId]);
            return null;
        }
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
        return Category::where($map)->first();
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
     * @return Category
     */
    public function getCateInfoByUuId($uuid) {
        return Category::GetByUuid($uuid);
    }

    /**
     * 根据ID获取目录详情
     * @param $id
     * @return Category
     */
    public function getCateInfoById($id) {
        return Category::where('id',$id)->first();
    }

    /**
     * @param $id
     * @return Category
     */
    public function getCategoryByIdOrUuid($id){
        if(is_string($id) && strlen($id) > 20){
            return $this->getCateInfoByUuId($id);
        }
        else{
            return $this->getCateInfoById($id);
        }
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

    /**
     * 获取学校的文件根目录. 如果学校的根目录不存在, 就创建一个
     *
     * @param $schoolId
     * @return mixed
     * @throws \Exception
     */
    public function getSchoolRootCategory($schoolId){
        $category = Category::where('school_id',$schoolId)
            ->where('type',Category::TYPE_SCHOOL_ROOT)
            ->first();

        if(!$category){
            // 表示学校的根文件目录不存在, 这个是不允许的, 需要创建它
            $dao = new SchoolDao();
            $school = $dao->getSchoolById($schoolId);

            $category = $this->create([
                'uuid'=>Uuid::uuid4()->toString(),
                'name'=>$school->name.'根目录',
                'type'=>Category::TYPE_SCHOOL_ROOT,
                'school_id'=>$schoolId,
                'owner_id'=>0,
                'parent_id'=>0
            ]);
        }
        return $category;
    }

    public function getAllSchoolRootCategory(){
        return Category::where('parent_id',0)->get();
    }
}
