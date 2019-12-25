<?php

namespace App\Models\Teachers;

use App\User;
use Illuminate\Database\Eloquent\Model;

class TeacherProfile extends Model
{
    protected $fillable = [
        'uuid',
        'user_id',
        'name',
        'school_id', // 教师任职的学校
        'serial_number', // 教师编号
        'group_name', // 所在部门: 基础教学部
        'gender',
        'title', // 教师职称: 教授, 讲师
        'id_number', // 身份证号
        'political_code',//政治面貌代码
        'political_name',//政治面貌名称
        'nation_code',//民族代码
        'nation_name',//民族名称
        'education',//学历
        'degree',//学位
        'birthday',
        'joined_at', // 入职日期
        'avatar',
        'famous',
        'work_start_at',
        'major',
        'final_education',
        'final_major',
        'title_start_at',
        'title1_at',
        'title1_hired_at',
        'hired_at',
        'hired',
        'notes',
    ];

    public $casts = ['famous'=>'boolean','hired'=>'boolean'];

    public $dates = ['joined_at'];

    /**
     * 获取教师ID
     * @return mixed
     */
    public function getTeacherId()
    {
        return $this->user_id;
    }

    /**
     * 获取教师所属学校ID
     * @return mixed
     */
    public function getTeacherSchoolId()
    {
        return $this->school_id;
    }


    public function user() {
        $field = ['id','name','mobile'];
        return $this->belongsTo(User::class)->select($field);
    }

    public function getAvatarAttribute($value){
        return asset($value ?? User::DEFAULT_USER_AVATAR);
    }
}
