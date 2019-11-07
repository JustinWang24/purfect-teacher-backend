<?php

namespace App\Dao\Schools;

use App\Models\Schools\GradeUser;

class GradeUserDao
{

    /**
     * 获取总数
     * @param $map
     * @return mixed
     */
    protected function getCount($map) {
        return GradeUser::where($map)->count();
    }


    /**
     * 通过班级Id集合获取学生总数
     * @param $map
     * @param $gradeIdArr
     * @return mixed
     */
    public function getCountByGradeIdArr($map,$gradeIdArr) {
        return GradeUser::where($map)->whereIn('grade_id',$gradeIdArr)->count();
    }
}
