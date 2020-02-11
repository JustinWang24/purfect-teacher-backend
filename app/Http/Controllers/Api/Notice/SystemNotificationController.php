<?php

namespace App\Http\Controllers\Api\Notice;

use App\Dao\Misc\SystemNotificationDao;
use App\Utils\JsonBuilder;
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
        $data = $dao->getNotificationByUser($user->getSchoolId(), $user);

        //设置消息为已读
        $dao->setNotificationHasRead($user->getSchoolId(), $user);
        return JsonBuilder::Success($data);
    }
}
