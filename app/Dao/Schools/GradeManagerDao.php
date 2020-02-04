<?php

namespace App\Dao\Schools;


use App\Models\Schools\GradeManager;

class GradeManagerDao
{

    /**
     * 根据班级ID 获取
     * @param $gradeId
     * @return GradeManager
     */
    public function getGradMangerByGradeId($gradeId)
    {
        return GradeManager::where('grade_id', $gradeId)->first();
    }

    /**
     * 根据班主任ID 获取班级
     * @param $adviser
     * @return mixed
     */
    public function getAllGradesByAdviserId($adviser)
    {
        return GradeManager::where('adviser_id', $adviser)->get();
    }

    /**
     * 根据班主任ID获取带的班级
     * @param $adviserId
     * @return mixed
     */
    public function getGradeManagerByAdviserId($adviserId) {
        return GradeManager::where('adviser_id',$adviserId)
            ->orderBy('created_at','desc')->get();
    }


}
