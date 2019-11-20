<?php

namespace App\Http\Requests\NetworkDisk;

use Ramsey\Uuid\Uuid;
use App\Utils\JsonBuilder;
use App\Dao\Users\GradeUserDao;
use App\Models\NetworkDisk\Category;
use App\Dao\NetworkDisk\CategoryDao;
use App\Utils\ReturnData\MessageBag;
use App\Http\Requests\MyStandardRequest;

class CategoryRequest extends MyStandardRequest
{

    /**
     * 获取目录的uuid
     * @return mixed
     */
    public function getCateUuId() {
        return $this->get('uuid',null);
    }


    /**
     * 获取被创建目录的上级目录的uuid
     * @return mixed
     */
    public function getParentId() {
        return $this->get('parent',null);
    }

    /**
     * 被创建目录的名称
     * @return mixed
     */
    public function getName() {
        return $this->get('name',null);
    }


    /**
     * 获取是否公开
     * @return mixed
     */
    public function getPublic() {
        return $this->get('public',null);
    }


    /**
     * 获取是否有星标
     * @return mixed
     */
    public function getAsterisk() {
        return $this->get('asterisk',null);
    }

    /**
     * 获取学校ID
     * @return mixed
     */
    public function getSchoolId() {
        return $this->user()->getSchoolId();
    }

    /**
     * 获取创建目录数据
     * @return MessageBag
     * @throws \Exception
     */
    public function getCreate() {

        $schoolId = $this->getSchoolId();
        if(is_null($schoolId)) {
            return new MessageBag(JsonBuilder::CODE_ERROR,'查询不到该用户的学校信息');
        }
        $name = $this->getName();
        $parentId = $this->getParentId();
        $categoriesDao = new CategoryDao();
        $parentCategory = $categoriesDao->getCategoryByIdOrUuid($parentId);

        if(!$parentCategory){
            return new MessageBag(JsonBuilder::CODE_ERROR,'指定的上级目录不存在');
        }

        if(!$parentCategory->isOwnedByUser($this->user())){
            return new MessageBag(JsonBuilder::CODE_ERROR,'你没有创建该目录的权限');
        }

        $exist = $categoriesDao->isExist($name, $this->user()->id, $parentCategory->id);

        if($exist) {
            return new MessageBag(JsonBuilder::CODE_ERROR,'该目录已存在');
        }

        $data = [
            'parent_id' => $parentCategory->id,
            'name'      => $name,
            'school_id' => $schoolId,
            'owner_id'  => $this->user()->id,
            'uuid'      => Uuid::uuid4()->toString(),
            'type'      => $this->getType(),
        ];
        return new MessageBag(JsonBuilder::CODE_SUCCESS,'请求成功',$data);
    }


    /**
     * 获取目录类型 2:根目录 4:子目录
     * @return int
     */
    public function getType() {
        return Category::TYPE_USER_SUBORDINATE;
    }


    /**
     * 获取修改内容
     * @return array
     */
    public function getEdit() {
        $parentId = $this->getParentId();
        $name = $this->getName();
        $public = $this->getPublic();
        $asterisk = $this->getAsterisk();
        $data = [];
        if(!is_null($parentId)) {
            $data['parent_id'] = $parentId;
        }
        if(!is_null($name)) {
            $data['name'] = $name;
        }
        if(!is_null($public)) {
            $data['public'] = $public;
        }
        if(!is_null($asterisk)) {
            $data['asterisk'] = $asterisk;
        }
        return $data;
    }
}
