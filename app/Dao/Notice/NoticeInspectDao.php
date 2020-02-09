<?php

namespace App\Dao\Notice;

use App\Models\Notices\NoticeInspect;

class NoticeInspectDao
{

    /**
     * 根据学校ID 获取检查类型
     * @param $schoolId
     * @return mixed
     */
    public function getInspectsBySchoolId($schoolId)
    {
        return NoticeInspect::select(['id','name'])->where('school_id', $schoolId)->get();
    }

}
