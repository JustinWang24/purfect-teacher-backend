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
}
