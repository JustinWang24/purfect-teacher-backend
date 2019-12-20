<?php

namespace App\Http\Controllers\Api\Notice;

use App\Dao\Misc\SystemNotificationDao;
use App\Models\Misc\SystemNotification;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SystemNotificationController extends Controller
{
    //
    /**
     * 通知公告列表
     * @param Request $request
     * @return
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $dao = new SystemNotificationDao();
        $data = $dao->getNotificationByUserId($request->get('school'), $user->id);
//        $json_array = [];
//        foreach($data as $key=>$value) {
//            $json_array[$key]['ticeid']= $value->id;
//            $json_array[$key]['create_at']= $value->create_id;
//            $json_array[$key]['tice_title']= $value->title;
//            $json_array[$key]['tice_content']= $value->content;
//            $json_array[$key]['tice_money']= $value->money;
//            $json_array[$key]['webview_url']= $value->next_move;
//            $json_array[$key]['type']= $value->type;
//            $json_array[$key]['priority']= $value->priority;
//            if (isset(SystemNotification::CATEGORY[$value->category])){
//                $json_array[$key]['tice_header']= SystemNotification::CATEGORY[$value->category];
//            } else {
//                $json_array[$key]['tice_header'] = '消息';
//            }
//        }
        return JsonBuilder::Success(['messages'=>$data->items()]);
    }
}
