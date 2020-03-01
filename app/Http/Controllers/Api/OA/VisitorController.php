<?php


namespace App\Http\Controllers\Api\OA;

use App\Dao\OA\VisitorDao;
use App\Utils\JsonBuilder;
use App\Http\Controllers\Controller;
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
                $val['memberCount'] = count(json_decode($val['visitors_json1'], true));
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
            $infos['memberList'] = json_decode($infos['visitors_json1'], true);
            $infos['memberCount'] = count($infos['memberList']);
            $infos['vehicleList'] = json_decode($infos['visitors_json2'], true);
            $infos['statusInfo'] = $visitorObj->getVisitorStatusInfo($infos);
            unset($infos['status'],$infos['visitors_json1'], $infos['visitors_json2']);
        }

        return JsonBuilder::Success($infos,'访客详情');
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
        $mobileArr = json_decode($mobile_json, true);
        if (!empty($mobileArr)) $mobileArr = array_filter(array_unique($mobileArr));
        if ($cate_id == 3 && empty($mobileArr)) {
            return JsonBuilder::Error('手机号不能为空');
        }

        $user = $request->user();

        $visitorObj = new VisitorDao();
        $infos = $visitorObj->getShareInfo($user->id);

        // 1:微信,2:QQ
        if (in_array($cate_id, [1, 2])) {
            // 添加数据
            $addData['uuid'] = $infos['uuid'];
            $addData['user_id'] = $user->gradeUserOneInfo->id;
            $addData['invited_by'] = $user->gradeUserOneInfo->id;
            $addData['school_id'] = $user->gradeUserOneInfo->school_id;
            $addData['cate_id'] = $cate_id;
            $addData['share_url'] = $infos['share_url'];
            $visitorObj->addVisitorInfo($addData);
        }

        // 短信
        if ($cate_id == 3) {
            foreach ($mobileArr as $val) {
                $infos = $visitorObj->getShareInfo($user->id);
                $addData['mobile'] = $val;
                $addData['uuid'] = $infos['uuid'];
                $addData['user_id'] = $user->gradeUserOneInfo->id;
                $addData['invited_by'] = $user->gradeUserOneInfo->id;
                $addData['school_id'] = $user->gradeUserOneInfo->school_id;
                $addData['cate_id'] = $cate_id;
                $addData['share_url'] = $infos['share_url'];
                $visitorObj->addVisitorInfo($addData);
            }
        }
        return JsonBuilder::Success($infos,'分享详情');
    }
}
