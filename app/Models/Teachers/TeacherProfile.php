<?php

namespace App\Models\Teachers;

use Illuminate\Database\Eloquent\Model;

class TeacherProfile extends Model
{
    protected $fillable = [
        'uuid',
        'teacher_id',
        'school_id',
        'name',
        'gender',
        'country',
        'state',
        'city',
        'postcode',
        'address_line',
        'address_in_school',
        'device',
        'birthday',
        'avatar',
    ];

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
