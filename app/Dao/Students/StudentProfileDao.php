<?php

namespace App\Dao\Students;

use App\Dao\Users\UserDao;
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
     * 根据身份证或者手机号, 获取用户的 ID
     * @param $idNumber
     * @param $mobile
     * @return int|null
     */
    public function getUserIdByIdNumberOrMobile($idNumber, $mobile){
        $userId = null;
        if($idNumber){
            $sp = $this->getStudentInfoByIdNumber($idNumber);
            $userId = $sp->user_id ?? null;
        }
        if(!$userId && $mobile){
            $dao = new UserDao();
            $user = $dao->getUserByMobile($mobile);
            $userId = $user->id ?? null;
        }
        return $userId;
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

        $data = $data->toArray();
        $result = [];

        if (is_array($data) && !empty($data)) {
            $result['profile']['id']                = is_null($data['user']['id']) ? '' : $data['user']['id'];
            $result['profile']['name']              = is_null($data['user']['name']) ? '' : $data['user']['name'];
            $result['profile']['mobile']            = is_null($data['user']['mobile']) ? '' : $data['user']['mobile'];
            $result['profile']['email']             = is_null($data['user']['email']) ? '' : $data['user']['email'];
            $result['profile']['id_number']         = is_null($data['id_number']) ? '' : $data['id_number'];
            $result['profile']['gender']            = is_null($data['gender']) ? '' : $data['gender'];
            $result['profile']['nation_name']       = is_null($data['nation_name']) ? '' : $data['nation_name'];
            $result['profile']['political_name']    = is_null($data['political_name']) ? '' : $data['political_name'];
            $result['profile']['source_place']      = is_null($data['source_place']) ? '' : $data['source_place'];
            $result['profile']['country']           = is_null($data['country']) ? '' : $data['country'];
            $result['profile']['birthday']          = is_null($data['birthday']) ? '' : $data['birthday'];
            $result['profile']['qq']                = is_null($data['qq']) ? '' : $data['qq'];
            $result['profile']['wx']                = is_null($data['wx']) ? '' : $data['wx'];
            $result['profile']['parent_name']       = is_null($data['parent_name']) ? '' : $data['parent_name'];
            $result['profile']['parent_mobile']     = is_null($data['parent_mobile']) ? '' : $data['parent_mobile'];
            $result['profile']['examination_score'] = is_null($data['examination_score'])? '' : $data['examination_score'];

            foreach ($data['registration_informatics'] as $key => $val) {
                $result['applied'][$key] = $val;
                unset($result['applied'][$key]['user_id']);
            }
        }

        return $result;
    }

}
