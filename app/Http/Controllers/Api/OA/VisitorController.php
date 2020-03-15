<?php


namespace App\Http\Controllers\Api\OA;

use App\Dao\Users\UserDao;
use App\Dao\Schools\SchoolDao;
use App\Dao\OA\VisitorDao;
use App\Events\User\SendCodeVisiterMobileEvent;
use App\Utils\JsonBuilder;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\OA\VisitorRequest;

class VisitorController extends Controller
{
    /**
     * Func 访客列表
     * @param VisitorRequest $request
     * @return string
     */
    public function list(VisitorRequest $request)
    {
        $token = (String)$request->input('token', '');
        $typeid = (Int)$request->input('typeid', 1);
        $page = (Int)$request->input('page', 1);

        if ($token == '') {
            return JsonBuilder::Error('请先登录');
        }

        $user = $request->user();

        $visitorObj = new VisitorDao();
        $infos = $visitorObj->getVisitorListInfo($user->id, $typeid, $page);
        if (!empty($infos)) {
            foreach ($infos as $key => &$val) {
                $val['memberCount'] = !empty($val['visitors_json1'])?count(json_decode($val['visitors_json1'], true)):0;
                $val['statusInfo'] = $visitorObj->getVisitorStatusInfo($val);
                unset($val['status'], $val['visitors_json1'], $val['visitors_json2']);
            }
        }

        return JsonBuilder::Success($infos,'访客列表');
    }

    /**
     * Func 访客详情
     * @param VisitorRequest $request
     * @return string
     */
    public function detail(VisitorRequest $request)
    {
        $token = (String)$request->input('token', '');
        $id = (Int)$request->input('id', 0);

        if ($token == '') {
            return JsonBuilder::Error('请先登录');
        }
        if (!$id) {
            return JsonBuilder::Error('参数错误');
        }

        $user = $request->user();

        $visitorObj = new VisitorDao();
        $infos = $visitorObj->getVisitorOneInfo($id, $user->id);
        if (!empty($infos)) {
            $infos['memberList'] = !empty($infos['visitors_json1'])?json_decode($infos['visitors_json1'], true):[];
            $infos['memberCount'] = count($infos['memberList']);
            $infos['vehicleList'] = !empty($infos['visitors_json2'])?json_decode($infos['visitors_json2'], true):[];
            $infos['statusInfo'] = $visitorObj->getVisitorStatusInfo($infos);
            unset($infos['status'],$infos['visitors_json1'], $infos['visitors_json2']);
        }

        return JsonBuilder::Success($infos,'访客详情');
    }

    /**
     * Func 获取被访用户信息
     * @param VisitorRequest $request
     * @return string
     */
    public function info(Request $request)
    {
        $uuid = (String)$request->input('uuid', '');
        if ($uuid == '') {
            return JsonBuilder::Error('参数错误');
        }

        $visitorObj = new VisitorDao();
        $data = $visitorObj->getUuidVisitorOneInfo($uuid);

        // 返回信息
        $infos['name'] = '';
        if (!empty($data)) {
            $userObj = new UserDao();
            $userInfo = $userObj->getUserById($data['user_id']);
            $infos['name'] = isset($userInfo->name) ? $userInfo->name : ''; // 被访人
        }
        return JsonBuilder::Success($infos, '被访信息');
    }

    /**
     * Func 更新
     * @param VisitorRequest $request
     * @return string
     */
    public function update(Request $request)
    {
        $uuid = (String)$request->input('uuid', '');
        $device = (String)$request->input('device', '');
        $visitors_json1 = (String)$request->input('visitors_json1', ''); // 人员信息
        $visitors_json2 = (String)$request->input('visitors_json2', ''); // 车辆信息
        $scheduled_at = (String)$request->input('scheduled_at', ''); // 来访时间
        $reason = (String)$request->input('reason', ''); // 来访描述

        $visitors_json1_arr = json_decode($visitors_json1, true); // 人员信息
        $visitors_json2_arr = json_decode($visitors_json2, true); // 车辆信息
        $scheduled_at = strtotime($scheduled_at); // 来访时间

        if ($uuid == '') {
            return JsonBuilder::Error('参数错误');
        }
        if ($visitors_json1 == '' || empty($visitors_json1_arr)) {
            return JsonBuilder::Error('请填人员信息');
        }
        if ($visitors_json2 != '' && empty($visitors_json1_arr)) {
            return JsonBuilder::Error('车辆信息格式错误');
        }
        if (!$scheduled_at) {
            return JsonBuilder::Error('来访时间格式错误');
        }
        if ($scheduled_at < (time()-10*60)) {
            return JsonBuilder::Error('来访时间不能小于当前时间');
        }

        $visitorObj = new VisitorDao();
        $data = $visitorObj->getUuidVisitorOneInfo($uuid);
        if (empty($data)) {
            return JsonBuilder::Error('数据不存在');
        }
        if ($data['status'] != 1) {
           // return JsonBuilder::Error('数据已处理,不能重复操作');
        }

        // 添加数据
        $saveData['status'] = 2;
        $saveData['reason'] = $reason;
        $saveData['device'] = trim($device);
        $saveData['name'] = (String)$visitors_json1_arr[0]['name'];
        $saveData['mobile'] = (String)$visitors_json1_arr[0]['mobile'];
        $saveData['visitors_json1'] = json_encode($visitors_json1_arr);
        $saveData['visitors_json2'] = json_encode($visitors_json2_arr);
        $saveData['scheduled_at'] = date('Y-m-d H:i:s', $scheduled_at);
        if ($visitors_json2_arr) $saveData['vehicle_license'] = (String)$visitors_json2_arr[0]['title'];
        if($visitorObj->editVisitorInfo($saveData,$data['id']))
        {
            $userObj = new UserDao();
            $userInfo = $userObj->getUserById($data['user_id']);
            $infos['scheduled_at'] = $saveData['scheduled_at']; // 来访日期
            $infos['name'] = isset($userInfo->name) ? $userInfo->name : ''; // 被访人
            $infos['vehicle_is'] = !empty($visitors_json2_arr) ? '是' : '否'; // 是否开车
            $infos['vehicle_license'] = !empty($visitors_json2_arr) ? implode(',', array_column($visitors_json2_arr, 'title')) : ''; // 是否开车
            $infos['menber_count'] = count($visitors_json1_arr); // 来访人数
            $infos['qrcode_url'] = (String)$data['qrcode_url']; // 二维码

            return JsonBuilder::Success($infos,'提交访客信息');

        } else {
            return JsonBuilder::Error('操作失败,请稍后重试');
        }
    }

    /**
     * Func 获取访客信息
     * @param VisitorRequest $request
     * @return string
     */
    public function get_visiter_info(Request $request)
    {
        $uuid = (String)$request->input('uuid', '');
        $infos = [];
        if($uuid)
        {
            $visitorObj = new VisitorDao();
            $data = $visitorObj->getUuidVisitorOneInfo($uuid);
            if(!empty($data) && in_array($data['status'],[2,3]))
            {
              $visitors_json1_arr = json_decode($data['visitors_json1'],true);
              $visitors_json2_arr = json_decode($data['visitors_json2'],true);

              $userObj = new UserDao();
              $userInfo = $userObj->getUserById($data['user_id']);
              $infos['scheduled_at'] = $data['scheduled_at']; // 来访日期
              $infos['name'] = isset($userInfo->name) ? $userInfo->name : ''; // 被访人
              $infos['vehicle_is'] = !empty($visitors_json2_arr) ? '是' : '否'; // 是否开车
              $infos['vehicle_license'] = !empty($visitors_json2_arr) ? implode(',', array_column($visitors_json2_arr, 'title')) : ''; // 是否开车
              $infos['menber_count'] = count($visitors_json1_arr); // 来访人数
              $infos['qrcode_url'] = (String)$data['qrcode_url']; // 二维码
            }
        }
       return JsonBuilder::Success($infos,'获取访客信息');
    }

    /**
     * Func 获取分享地址
     * @param VisitorRequest $request
     * @return string
     */
    public function get_share_info(VisitorRequest $request)
    {
        $token = (String)$request->input('token', '');
        $cate_id = (Int)$request->input('cate_id', 0);
        $mobile_json = (String)$request->input('mobile_json', '');

        if (!in_array($cate_id, [1, 2, 3])) {
            return JsonBuilder::Error('参数错误');
        }

        // 如果是短信发送，需要验证手机号;
        $mobileArr = json_decode( stripslashes( $mobile_json ) , true );
        if (!empty($mobileArr)) $mobileArr = array_filter(array_unique($mobileArr));
        if ($cate_id == 3 && empty($mobileArr)) {
            return JsonBuilder::Error('手机号不能为空');
        }

        $user = $request->user();

        $schoolObj = new SchoolDao();
        $visitorObj = new VisitorDao();
        $infos = $visitorObj->getShareInfo($user->id);

        // 1:微信,2:QQ
        if (in_array($cate_id, [1, 2])) {
            $addData['uuid'] = $infos['uuid'];
            $addData['user_id'] = $user->gradeUserOneInfo->user_id;
            $addData['invited_by'] = $user->gradeUserOneInfo->user_id;
            $addData['school_id'] = $user->gradeUserOneInfo->school_id;
            $addData['cate_id'] = $cate_id;
            $addData['share_url'] = $infos['share_url'];
            $addData['qrcode_url'] = $infos['qrcode_url'];
            $visitorObj->addVisitorInfo($addData);
        }

        // 短信
        if ($cate_id == 3) {
            foreach ($mobileArr as $val) {
                $infos = $visitorObj->getShareInfo($user->id);
                $addData['mobile'] = $val;
                $addData['uuid'] = $infos['uuid'];
                $addData['user_id'] = $user->gradeUserOneInfo->user_id;
                $addData['invited_by'] = $user->gradeUserOneInfo->user_id;
                $addData['school_id'] = $user->gradeUserOneInfo->school_id;
                $addData['cate_id'] = $cate_id;
                $addData['share_url'] = $infos['share_url'];
                $addData['qrcode_url'] = $infos['qrcode_url'];
                $visitorObj->addVisitorInfo($addData);

                // 发送验证码
                $schoolInfo = $schoolObj->getSchoolById($user->gradeUserOneInfo->school_id);
                event(new SendCodeVisiterMobileEvent($user, $val, $schoolInfo->name, $infos['share_url']));
            }
        }

        // 获取学校信息
        $infos['school_name'] = '';
        $infos['school_logo'] = '';
        if (isset($user->gradeUserOneInfo->school_id)) {
            $schoolInfo = $schoolObj->getSchoolById($user->gradeUserOneInfo->school_id);
            $infos['school_name'] = (String)$schoolInfo->name;
            $infos['school_logo'] = (String)$schoolInfo->logo;
        }
        unset($infos['qrcode_url']);

        return JsonBuilder::Success($infos, '分享详情');
    }
}
