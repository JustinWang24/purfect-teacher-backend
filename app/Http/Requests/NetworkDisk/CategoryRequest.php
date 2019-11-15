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
        return $this->get('parent_id',null);
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
        $userId = $this->user()['id'];
        $gradeUserDao = new GradeUserDao();
        return $gradeUserDao->getSchoolIdByUserId($userId);
    }


    /**
     * 获取创建目录数据
     * @return MessageBag
     * @throws \Exception
     */
    public function getCreate() {

        $re = $this->getSchoolId();
        if(is_null($re)) {
            return new MessageBag(JsonBuilder::CODE_ERROR,'查询不到该用户的学校信息');
        }
        $name = $this->getName();
        $userId = $this->user()['id'];
        $parentId = $this->getParentId();
        $categoriesDao = new CategoryDao();
        $re = $categoriesDao->isExist($name, $userId, $parentId);
        if(!$re) {
            return new MessageBag(JsonBuilder::CODE_ERROR,'该目录已存在');
        }
        $data = [
            'parent_id' => $parentId,
            'name'      => $name,
            'school_id' => $re['school_id'],
            'owner_id'  => $userId,
            'uuid'      => Uuid::uuid4()->toString(),
            'type'      => $this->getType(),
        ];
        return new MessageBag(JsonBuilder::CODE_SUCCESS,'请求成功',$data);
    }


    /**
     * 获取目录类型 0:根目录 1:子目录
     * @return int
     */
    public function getType() {
        $userId = $this->user()['id'];
        $categoriesDao = new CategoryDao();
        $re = $categoriesDao->getMyCategoryByUserId($userId,Category::TYPE_USER_ROOT);
        if(is_null($re)) {
            return 0;
        } else {
            return 1;
        }
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
