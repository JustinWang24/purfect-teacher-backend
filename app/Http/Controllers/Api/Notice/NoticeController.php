<?php


namespace App\Http\Controllers\Api\Notice;


use App\Models\Notices\Notice;
use App\Utils\JsonBuilder;
use App\Dao\Notice\NoticeDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Notice\NoticeRequest;

class NoticeController extends Controller
{

    /**
     * 前端 APP 获取通知列表
     * @param NoticeRequest $request
     * @return string
     */
    public function getNotice(NoticeRequest $request) {
        $userId = $request->user()->id;
        $type = $request->getType();
        $dao = new NoticeDao();
        $schoolId = $request->user()->getSchoolId();
        $pageNumber = $request->get('page',1);
        $result = $dao->getNotice($type, $schoolId, $pageNumber - 1);

        foreach ($result['notices'] as $key => $item) {
            $re = $item->readLog($userId);
            if(is_null($re)) {
                $item->is_read = Notice::UNREAD; // 未读
            } else {
                $item->is_read = Notice::READ; // 已读
            }
        }
        $data = pageReturn($result['notices'], $result['total'], $pageNumber);
        return JsonBuilder::Success($data);
    }

    /**
     * 消息通知前端接口
     * @param NoticeRequest $request
     * @return string
     */
    public function noticeInfo(NoticeRequest $request) {
        $noticeId = $request->getNoticeId();
        $userId = $request->user()->id;
        $dao = new NoticeDao();
        $result = $dao->getNoticeById($noticeId);
        if(is_null($result)) {
            return JsonBuilder::Error('该通知不存在');
        }
        $data = ['notice_id'=>$noticeId, 'user_id'=>$userId];
        // 添加阅读记录
        $dao->addReadLog($data);
        return JsonBuilder::Success(['notice'=>$result]);
    }
}
