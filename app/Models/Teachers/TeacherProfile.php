<?php

namespace App\Models\Teachers;

use Illuminate\Database\Eloquent\Model;

class TeacherProfile extends Model
{

    /**
     * 获取教师ID
     * @return mixed
     */
    public function getTeacherId()
    {
        return $this->teacher_id;
    }

    /**
     * 获取教师所属学校ID
     * @return mixed
     */
    public function getTeacherSchoolId()
    {
        return $this->school_id;
    }


}
