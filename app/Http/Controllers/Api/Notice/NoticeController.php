<?php


namespace App\Http\Controllers\Api\Notice;


use App\Utils\JsonBuilder;
use App\Dao\Notice\NoticeDao;
use App\Models\Notices\Notice;
use App\Http\Controllers\Controller;
use App\Http\Requests\Notice\NoticeRequest;

class NoticeController extends Controller
{

    /**
     * 获取通知列表 指定的部门才能看见
     * @param NoticeRequest $request
     * @return string
     */
    public function getNotice(NoticeRequest $request) {
        $user = $request->user();
        $organizations = $user->organizations;
        $organizationId = $organizations->pluck('organization_id')->toArray();
        $type = $request->getType();
        $dao = new NoticeDao();
        $schoolId = $user->getSchoolId();
        $result = $dao->getNotice($type, $schoolId, $organizationId);
        foreach ($result as $key => $item) {
            $item->notice->attachment_field = 'url';
            $item->attachments = $item->notice->attachments;
            $re = $item->notice->readLog($user->id);

            if(is_null($re)) {
                $item->is_read = Notice::UNREAD; // 未读
            } else {
                $item->is_read = Notice::READ; // 已读
            }

            unset($item->notice);
            $inspect = $item->inspect;
            unset($item->inspect);
            $item->inspect = $inspect->name ?? '';
        }
        $data = pageReturn($result);
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


    /**
     * 发布通知
     * @param NoticeRequest $request
     * @return string
     */
    public function issueNotice(NoticeRequest $request) {
        $user = $request->user();
        $data = $request->all();
        $data['school_id'] = $user->getSchoolId();
        $data['user_id'] = $user->id;
        $data['type'] = Notice::TYPE_NOTIFY;
        $organizationIds = $data['organization_id'];

        unset($data['attachments']);
        unset($data['organization_id']);
        $file = $request->file('attachments');
        $dao = new NoticeDao();
        $result = $dao->issueNotice($data, $organizationIds, $file, $user);

        $msg = $result->getMessage();
        if($result->isSuccess()) {
            $data = $result->getData();
            return JsonBuilder::Success($data, $msg);
        } else {
            return JsonBuilder::Error($msg);
        }

    }

}
