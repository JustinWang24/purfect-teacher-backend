<?php

namespace App\Dao\Schools;

use App\Models\Acl\Role;
use App\Models\Users\GradeUser;

class GradeUserDao
{

    /**
     * 通过班级ID或者班级ID集合查询学生总数
     * @param $gradeId
     * @return mixed
     */
    public function getCountByGradeId($gradeId) {

        $userType = Role::GetStudentUserTypes();
        $result = GradeUser::whereIn('user_type',$userType);
        if(is_array($gradeId)) {
            $result = $result->whereIn('grade_id',$gradeId);
        } elseif (is_numeric($gradeId)) {
            $result = $result->where('grade_id',$gradeId);
        }

        return $result->count();
    }



}
