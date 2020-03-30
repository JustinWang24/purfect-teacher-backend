<?php

namespace App\Http\Controllers\Api\Notice;

use App\Dao\Misc\SystemNotificationDao;
use App\Utils\JsonBuilder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SystemNotificationController extends Controller
{
    //
    /**
     * 系统消息列表
     * @param Request $request
     * @return
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $dao = new SystemNotificationDao();
        $data = $dao->getNotificationByUser($user->getSchoolId(), $user)->toArray();
        foreach ($data['data'] as &$systemNotification) {
            $retExtra = [
                'type' => '',
                'param1' => '',
                'param2' => ''
            ];
            if (!empty($systemNotification['app_extra'])) {
                $extra = json_decode($systemNotification['app_extra'], true);
                $retExtra = [
                    'type' => $extra['type'],
                    'param1' => strval($extra['param1']),
                    'param2' => strval($extra['param2'])
                ];
            }
            $systemNotification['content'] = strip_tags($systemNotification['content']);
            $systemNotification['app_extra'] = $retExtra;
            $systemNotification['created_at'] = Carbon::parse($systemNotification['created_at'])->format('Y-m-d H:i');
        }
        //设置消息为已读
        $dao->setNotificationHasRead($user->getSchoolId(), $user);
        return JsonBuilder::Success($data);
    }
}
