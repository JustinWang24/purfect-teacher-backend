<?php

namespace App\Models\Students;

use App\Models\RecruitStudent\RegistrationInformatics;
use App\User;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    protected $fillable = [
        'uuid',
        'user_id',
        'device',
        'year', // 招生年度
        'serial_number', // 录取编号
        'gender',
        'country',
        'state',
        'city',
        'postcode',
        'area', // 地区名称
        'address_line',
        'address_in_school',
        'student_number', // 考生号
        'license_number', // 准考证号
        'id_number', // 身份证号
        'birthday',
        'avatar',
        'political_code', // 政治面貌代码
        'political_name', // 政治面貌名称
        'nation_code', // 民族代码
        'nation_name', // 民族名称
        'parent_name', // 家长姓名
        'parent_mobile' // 家长手机号
    ];

    public $dates = ['birthday'];

    protected $table = 'student_profiles';


    public function user()
    {
        return $this->hasOne(User::class,'id', 'user_id');
    }

    public function registrationInformatics()
    {
        return $this->hasMany(RegistrationInformatics::class,'user_id', 'user_id');
    }


}
