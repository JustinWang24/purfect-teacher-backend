<?php

namespace App\Dao\Schools;

use App\Models\School;
use App\Models\Schools\SchoolResource;

class SchoolResourceDao
{

    /**
     * 根据 uuid 或者 id 获取学校资源
     * @param $schoolIdOrUuid
     * @param int $condition
     * @return School|null
     */
    public function getSchoolResourceBySchoolIdOrUuid($schoolIdOrUuid, $condition = 0)
    {
        if(is_string($schoolIdOrUuid) && strlen($schoolIdOrUuid) > 10) {
            $where['uuid'] = $schoolIdOrUuid;
        } else {
            $where['school_id'] = $schoolIdOrUuid;
        }

        // 1 图片 2 视频 0 所有
        if ($condition == SchoolResource::TYPE_IMAGE) {
            $where['type'] = SchoolResource::TYPE_IMAGE;
        }
        elseif ($condition == SchoolResource::TYPE_VIDEO) {
            $where['type'] = SchoolResource::TYPE_VIDEO;
        }

        return SchoolResource::where($where)->get();
    }

    /**
     * 获取学校所有图片
     * @param $schoolIdOrUuid
     * @return School|null
     */
    public function getSchoolImgBySchoolIdOrUuId($schoolIdOrUuid)
    {
        if(is_string($schoolIdOrUuid) && strlen($schoolIdOrUuid) > 10) {
            $where['uuid'] = $schoolIdOrUuid;
            $where['type'] = SchoolResource::TYPE_IMAGE;
        }
        elseif (is_int($schoolIdOrUuid)) {
            $where['school_id'] = $schoolIdOrUuid;
            $where['type'] = SchoolResource::TYPE_IMAGE;
        } else {
            return  false;
        }

        return SchoolResource::where($where)->orderBy('created_at', 'desc')->get();
    }

    /**
     * 获取学校视频
     * @param $schoolIdOrUuid
     * @return School
     */
    public function getSchoolVideoBySchoolIdOrUuId($schoolIdOrUuid)
    {
        if(is_string($schoolIdOrUuid) && strlen($schoolIdOrUuid) > 10) {
            $where['uuid'] = $schoolIdOrUuid;
            $where['type'] = SchoolResource::TYPE_VIDEO;
        }
        elseif (is_int($schoolIdOrUuid)) {
            $where['school_id'] = $schoolIdOrUuid;
            $where['type'] = SchoolResource::TYPE_VIDEO;
        }
        else {
           return  false;
        }

        return SchoolResource::where($where)->orderBy('created_at', 'desc')->first();
    }

    /**
     * 分页 获取学校所以资源
     * @param $schoolId
     * @return School
     */
    public function getPagingSchoolResourceBySchoolId($schoolId)
    {
        $where['school_id'] = $schoolId;
        return SchoolResource::where($where)
                            ->orderBy('sort', 'desc')
                            ->orderBy('created_at', 'desc')
                            ->paginate(SchoolResource::PAGE_NUMBER);
    }

    /**
     * 添加风采
     * @param $data
     * @return bool
     */
    public function addSchoolResource($data)
    {
        return SchoolResource::create($data);
    }

    /**
     * 修改
     * @param $data
     */
    public function  updateSchoolResource($data)
    {

    }

}
