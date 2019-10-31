<?php

namespace  App\Dao\RecruitStudent;

use App\Dao\Schools\MajorDao;

class PlanRecruitDao
{
    /**
     * 通过学校ID获取专业信息
     * @param $schoolId
     * @return mixed
     */
    public function getPlanRecruitBySchoolId($schoolId) {
        $majorDao = new MajorDao();
        $map = ['school_id'=>$schoolId];
        $field = ['id', 'name', 'description', 'fee', 'open', 'seats'];
        $list = $majorDao->getMajorPage($map, $field);
        return $list;
    }

    /**
     * 获取预招信息
     * @param $majorId
     * @return \App\Models\Schools\Major|null
     */
    public function getPlanRecruitInfo($majorId) {
        $majorDao = new MajorDao();
        $result = $majorDao->getMajorById($majorId);
        return $result;
    }


    /**
     * 修改预招信息
     * @param $data
     * @param $user
     * @return \App\Models\Schools\Major
     */
    public function updPlanRecruit($data, $user) {
        $majorDao = new MajorDao($user);
        $result = $majorDao->updateMajor($data);
        return $result;
    }

}
