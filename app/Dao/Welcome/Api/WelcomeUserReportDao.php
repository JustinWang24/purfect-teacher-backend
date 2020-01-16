<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Dao\Welcome\Api;

use App\Models\Students\StudentProfile;
use App\Dao\Welcome\Api\WelcomeConfigDao;
use App\Models\Welcome\Api\WelcomeConfig;
use App\Models\Welcome\Api\WelcomeUserReport;

use App\Utils\JsonBuilder;
use Illuminate\Support\Collection;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Support\Facades\DB;

class WelcomeUserReportDao
{

    /**
     * Func 获取招生办过来的基础信息
     *
     * @param $userId 用户Id
     * @param $fieldArr 字段信息
     *
     * @return Collection
     */
    public function getStudentProfilesInfo( $userId , $fieldArr = ['*'] )
    {
        if (!intval($userId)) return [];

        return StudentProfile::where('user_id', $userId)->select($fieldArr)->first();
    }

    /**
     * Func 获取用户已报到信息
     *
     * @param $userId 用户Id
     * @param $fieldArr 字段信息
     *
     * @return Collection
     */
    public function getWelcomeUserReportOneInfo( $userId , $fieldArr = ['*'] )
    {
        return  WelcomeUserReport::where('user_id', $userId)
            ->where('status', '>', 0)->select($fieldArr)->first();
    }


    /**
     * Func 添加报到信息
     *
     * @param $data 基础信息
     *
     * @return false|id
     */
    public function saveWelcomeUserReportsInfo($data = [])
    {
        if (empty($data))
        {
            return false;
        }
        DB::beginTransaction();
        try {
            if ($id = WelcomeUserReport::create($data)) {
                DB::commit();
                return $id;
            } else {
                DB::rollBack();
                return false;
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }


    /**
     * Func 更新报到信息
     *
     * @param $id 更新Id
     * @param $data 基础信息
     *
     * @return false|id
     */
    public function updateWelcomeUserReportsInfo($data = [], $id = 0)
    {
        if (!intval($id) || empty($data))
        {
            return false;
        }
        DB::beginTransaction();
        try {
            if (WelcomeUserReport::where('configid',$id)->update($data)) {
                DB::commit();
                return $id;
            } else {
                DB::rollBack();
                return false;
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Func 验证学校迎新配置
     * Desc 验证当前用户是否可以迎新
     *
     * @param $campusId 校区ID
     * @param $userId 用户ID
     *
     * @return array
     */
    public function checkUserIsWelcomeInfo($campusId = 0, $userId = 0)
    {
        if (!intval($campusId) || !intval($userId)) {
            return array('status' => 0, 'notice' => '','message' => '参数错误');
        }

        $fieldArr = ['config_sdata', 'config_edate', 'status'];

        $getWelcomeConfigOneInfo = (new WelcomeConfigDao)->getWelcomeConfigOneInfo($campusId, $fieldArr);

        // 学校还未配置迎新
        if (empty($getWelcomeConfigOneInfo)) {
            return array('status' => 0, 'notice' => '','message' => '学校未配置迎新');
        }

        // 学校已关闭迎新
        if ($getWelcomeConfigOneInfo->status != 1) {
            return array('status' => 0, 'notice' => '','message' => '学校已关闭迎新');
        }

        // 迎新未开始
        if (time() < strtotime($getWelcomeConfigOneInfo->config_sdata)) {
            return array('status' => 0, 'notice' => '','message' => '迎新未开始');
        }

        // 迎新已结束
        if (time() > strtotime($getWelcomeConfigOneInfo->config_edate)) {
            return array('status' => 0, 'notice' => '','message' => '迎新已结束');
        }

        // 获取用户信息是否存在
        if (empty($this->getStudentProfilesInfo($userId))) {
            return array('status' => 0, 'notice' => '', 'message' => '未查找到您的信息');
        }

        // 学校配置验证通过
        return array('status' => 1, 'notice' => '', 'message' => '');
    }

    /**
     * Func 按照用户节点验证配置
     *
     * @param $reportOneInfo 用户迎新内容
     *
     * @return true|false
     */
    public function checkWelcomeUserReportOneInfo($reportOneInfo = [],$letter = null )
    {
        // 个人信息完善
        if($letter == 'A')
        {
            if(empty($reportOneInfo->steps_2_date))
            {
                return array('status' => 2 ,'notice' => '', 'message' => '请完善个人信息');
            } else {
                return array('status' => 1 ,'notice' => '已完成', 'message' => '');
            }
        }

        // 扫码报到
        if($letter == 'B')
        {
            if(empty($reportOneInfo->steps_2_date))
            {
                return array('status' => 0 ,'notice' => '', 'message' => '请完善个人信息');
            }

            // 是否报到
            if(empty($reportOneInfo->complete_date))
            {
                return array('status' => 2, 'notice' => '', 'message' => '');
            } else {
                return array('status' => 1, 'notice' => '已完成', 'message' => '');
            }
        }

        // 报到单
        if($letter == 'C')
        {
            // 个人信息完善
            if(empty($reportOneInfo->steps_2_date))
            {
                return array('status' => 0, 'notice' => '', 'message' => '请完善个人信息');
            }

            // 是否报到
            if(empty($reportOneInfo->complete_date))
            {
                return array('status' => 0, 'notice' => '', 'message' => '您还没有报到');
            }
        }

        return array('status'=>1, 'notice' => '', 'message'=>'');
    }

    /**
     * Func 获取用户信息
     * Desc 如果用户已报到，就获取报到信息，如果未报到就获取招生办用户信息
     *
     * @param $userId 用户ID
     *
     * @return false|array
     */
    public function getUserReportOrUserProfilesInfo($userId = 0)
    {
        if (!intval($userId)) {
            return false;
        }

        // 获取用户基础信息
        $WelcomeUserReportDao = new WelcomeUserReportDao();

        $userReportArr = [];

        $getWelcomeUserReportOneInfo = $this->getWelcomeUserReportOneInfo($userId);

        if (!empty($getWelcomeUserReportOneInfo) && $getWelcomeUserReportOneInfo->steps_1_str != '')
        {
            // 如果用户之前未保存过信息，就获取招生办信息
            $userReportArr = json_decode($getWelcomeUserReportOneInfo->steps_1_str,true);
        } else {
            $getStudentProfilesInfo = $this->getStudentProfilesInfo($userId);

            // 获取学生基础信息
            $fieldArr = [
                'users.name as user_name', // 姓名
                'users.mobile as user_mobile', // 手机号
                'users.email as email', // 邮箱
                'institutes.id as institute_id', // 学院ID
                'institutes.name as institute_name', // 学院ID
                'majors.id as major_id', // 专业ID
                'majors.name as major_name', // 专业ID
                'grades.id as class_id', // 班级ID
                'grades.name as class_name', // 专业ID
                'majors.period as level', // 学制
                'majors.name as class_name', // 专业ID
            ];
            $baseUserInfo = DB::table('grade_users')
                ->where ('user_id',$userId )
                ->join('users','grade_users.user_id','=','users.id') // 用户信息
                ->join('institutes','grade_users.institute_id','=','institutes.id') // 学院信息
                ->join('majors','grade_users.major_id','=','majors.id') // 专业信息
                ->join('grades','grade_users.major_id','=','grades.id') // 班级信息
                ->select($fieldArr)
                ->first ();

        }

        // 返回用户信息
        $data['student_id'] = !empty($userReportArr) ? (String)$userReportArr['serial_number'] : (isset($getStudentProfilesInfo->serial_number) ? (String)$getStudentProfilesInfo->serial_number : '');; // 学号
        $data['user_name'] = !empty($userReportArr) ? $userReportArr['user_name'] : (String)$baseUserInfo->user_name; // 姓名
        $data['id_number'] = !empty($userReportArr) ? (String)$userReportArr['id_number'] : (isset($getStudentProfilesInfo->id_number) ? (String)$getStudentProfilesInfo->id_number : ''); // 身份证号
        $data['gender'] = !empty($userReportArr) ? (String)$userReportArr['gender'] : (isset($getStudentProfilesInfo->gender) ? (String)$getStudentProfilesInfo->gender : 1); // 性别(1:男 2:女 )
        $data['birthday'] = !empty($userReportArr) ? (String)$userReportArr['birthday'] : (isset($getStudentProfilesInfo->birthday) ? date('Y-m-d',strtotime($getStudentProfilesInfo->birthday)) : ''); // 出身日期(2020-01-12)
        $data['nation_id'] = !empty($userReportArr) ? (String)$userReportArr['nation_id'] : (isset($getStudentProfilesInfo->nation_code) ? (String)$getStudentProfilesInfo->nation_code : ''); // 民族代码
        $data['nation_name'] = !empty($userReportArr) ? (String)$userReportArr['nation_name'] : (String)$getStudentProfilesInfo->nation_name; // 民族名称
        $data['political_id'] = !empty($userReportArr) ? (String)$userReportArr['political_id'] : (String)$getStudentProfilesInfo->political_code; // 政治面貌代码
        $data['political_name'] = !empty($userReportArr) ? (String)$userReportArr['political_name'] : (String)$getStudentProfilesInfo->political_name; // 政治面貌代码

        $data['level'] = !empty($userReportArr) ? $userReportArr['level'] : (String)$baseUserInfo->level; // 学制
        $data['education_id'] = 1; // 学历
        $data['education_name'] = '中专'; // 学历nation_code
        $data['institute_id'] = !empty($userReportArr) ? $userReportArr['institute_id'] : (String)$baseUserInfo->institute_id;; // 学院id
        $data['institute_name'] = !empty($userReportArr) ? $userReportArr['institute_name'] : (String)$baseUserInfo->institute_name;; // 学院名称
        $data['major_id'] = !empty($userReportArr) ? $userReportArr['major_id'] : (String)$baseUserInfo->major_id;; // 专业id
        $data['major_name'] = !empty($userReportArr) ? $userReportArr['major_name'] : (String)$baseUserInfo->major_name;; // 专业名称
        $data['class_id'] = !empty($userReportArr) ? $userReportArr['class_id'] : (String)$baseUserInfo->class_id;; // 班级id
        $data['class_name'] = !empty($userReportArr) ? $userReportArr['class_name'] : (String)$baseUserInfo->class_name;; // 班级名称
        $data['email'] = !empty($userReportArr) ? $userReportArr['email'] : (String)$baseUserInfo->email;; // 邮箱

        // 生源地nation_code
        $data['source_province_id'] = !empty($userReportArr) ? (String)$userReportArr['source_province_id'] : ''; // 省id
        $data['source_province_name'] = !empty($userReportArr) ? (String)$userReportArr['source_province_name'] : ''; // 省名称
        $data['source_city_id'] = !empty($userReportArr) ? (String)$userReportArr['source_city_id'] : ''; // 城市id
        $data['source_city_name'] = !empty($userReportArr) ? (String)$userReportArr['source_city_name'] : ''; // 城市名称
        $data['source_area_id'] = !empty($userReportArr) ? (String)$userReportArr['source_area_id'] : ''; // 地区id
        $data['source_area_nme'] = !empty($userReportArr) ? (String)$userReportArr['source_area_nme'] : ''; // 地区名称
        // $infos['source_place'] = !empty($userReportArr) ? (String)$userReportArr['source_place'] : (String)$getStudentProfilesInfo->political_name; // 生源地 这里$getStudentProfilesInfo 只有一个名称

        // 籍贯
        $data['place_province_id'] = !empty($userReportArr) ? (String)$userReportArr['place_province_id'] : ''; // 省id
        $data['place_province_name'] = !empty($userReportArr) ? (String)$userReportArr['place_province_name'] : ''; // 省名称
        $data['place_city_id'] = !empty($userReportArr) ? (String)$userReportArr['place_city_id'] : ''; // 城市id
        $data['place_city_name'] = !empty($userReportArr) ? (String)$userReportArr['place_city_name'] : ''; // 城市名称
        $data['place_area_id'] = !empty($userReportArr) ? (String)$userReportArr['place_area_id'] : ''; // 地区id
        $data['place_area_nme'] = !empty($userReportArr) ? (String)$userReportArr['place_area_nme'] : ''; // 地区名称
        // $infos['country'] = !empty($userReportArr) ? (String)$userReportArr['country'] : (String)$getStudentProfilesInfo->country; // 籍贯 这里$getStudentProfilesInfo 只有一个名称

        $data['telephone'] = !empty($userReportArr) ? (String)$userReportArr['telephone'] : ''; // 联系电话 $getStudentProfilesInfo 没有字段
        $data['qq'] = !empty($userReportArr) ? (String)$userReportArr['qq'] : (String)$getStudentProfilesInfo->qq; // QQ号
        $data['wx'] = !empty($userReportArr) ? (String)$userReportArr['wx'] : (String)$getStudentProfilesInfo->wx; // 微信号
        $data['parent_name'] = !empty($userReportArr) ? (String)$userReportArr['parent_name'] : (String)$getStudentProfilesInfo->parent_name; // 家长姓名
        $data['parent_mobile'] = !empty($userReportArr) ? (String)$userReportArr['parent_mobile'] : (String)$getStudentProfilesInfo->parent_mobile; // 家长手机号

        // 家庭住址
        $data['family_province_id'] = !empty($userReportArr) ? (String)$userReportArr['family_province_id'] : ''; // 省id
        $data['family_province_name'] = !empty($userReportArr) ? (String)$userReportArr['family_province_name'] : ''; // 省名称
        $data['family_city_id'] = !empty($userReportArr) ? (String)$userReportArr['family_city_id'] : ''; // 城市id
        $data['family_city_name'] = !empty($userReportArr) ? (String)$userReportArr['family_city_name'] : ''; // 城市名称
        $data['family_area_id'] = !empty($userReportArr) ? (String)$userReportArr['family_area_id'] : ''; // 地区id
        $data['family_area_nme'] = !empty($userReportArr) ? (String)$userReportArr['family_area_nme'] : ''; // 地区名称
        $data['family_detailed_address'] = !empty($userReportArr) ? (String)$userReportArr['family_detailed_address'] : (String)$getStudentProfilesInfo->detailed_address; // 详细地址

        return $data;
    }



}
