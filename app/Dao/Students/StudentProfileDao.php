<?php

namespace App\Dao\Students;

use App\Models\Students\StudentProfile;

class StudentProfileDao extends StudentProfile
{

    /**
     * 根据userId 获取学生信息
     * @param $userId
     * @return mixed
     */
    public function getStudentInfoByUserId($userId)
    {
        return StudentProfile::where('user_id', $userId)->first();
    }

    /**
     * 根据身份证号 获取学生信息
     * @param $idNumber
     * @return mixed
     */
    public function getStudentInfoByIdNumber($idNumber)
    {
        return StudentProfile::where('id_number', $idNumber)->first();
    }

    /**
     * 学生报名数据填充
     * @param $userId
     * @param $field
     * @return mixed
     */
    public function getStudentSignUpByUserId($userId, $field)
    {
        $data = StudentProfile::where('user_id', $userId)->select($field)->with([
                'registrationInformatics' => function ($query) {
                    $query->select('majors.id', 'user_id', 'status', 'relocation_allowed', 'majors.name')
                          ->join('majors', 'registration_informatics.major_id', '=', 'majors.id');
                },
                'user' => function ($query) {
                    $query->select('id', 'mobile', 'email', 'name');
                }
            ])->first();

        dd($data->toArray());
    }

}
