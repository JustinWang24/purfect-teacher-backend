<?php

namespace App\Dao\Schools;

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
        }
        elseif (is_int($schoolIdOrUuid)) {
            $where['school_id'] = $schoolIdOrUuid;
        }
        // 1 图片 2 视频 0 所有
        if ($condition == 1) {
            $where['type'] = 1;
        }
        elseif ($condition == 2) {
            $where['type'] = 2;
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
            $where['type'] = 1;
        }
        elseif (is_int($schoolIdOrUuid)) {
            $where['school_id'] = $schoolIdOrUuid;
            $where['type'] = 1;
        }

        return SchoolResource::where($where)->orderBy('created_at', 'desc')->get();
    }

    /**
     * 获取学校视频
     * @param $schoolIdOrUuid
     * @return School
     */
    public function getSchoolVideoSchoolIdOrUuId($schoolIdOrUuid)
    {
        if(is_string($schoolIdOrUuid) && strlen($schoolIdOrUuid) > 10) {
            $where['uuid'] = $schoolIdOrUuid;
            $where['type'] = 2;
        }
        elseif (is_int($schoolIdOrUuid)) {
            $where['school_id'] = $schoolIdOrUuid;
            $where['type'] = 2;
        }

        return SchoolResource::where($where)->orderBy('created_at', 'desc')->first();
    }



}
