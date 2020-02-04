<?php

namespace App\Http\Controllers\Operator;

use App\Dao\Schools\OrganizationDao;
use App\Events\SystemNotification\NoticeSendEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Notice\NoticeRequest;
use App\Dao\Notice\NoticeDao;
use App\Dao\Notice\NoticeInspectDao;
use App\Models\Notices\Notice;
use App\Utils\FlashMessageBuilder;
use App\Utils\JsonBuilder;

class NoticeController extends Controller
{

    /**
     * 通知公告列表
     * @param NoticeRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(NoticeRequest $request)
    {
        $schoolId = $request->getSchoolId();

        $orgDao = new OrganizationDao();
        $this->dataForView['organizations'] = $orgDao->getBySchoolId($schoolId);
        $dao = new NoticeDao;

        $search = $request->get('search');

        if ($search) {
            $where = ['school_id' => $schoolId, $search];
        } else {
            $where = ['school_id' => $schoolId];
        }

        $data                      = $dao->getNoticeBySchoolId($where);
        $this->dataForView['data'] = $data;
        $this->dataForView['schoolId'] = $schoolId;
        $this->dataForView['userRoles'] = null;
        return view('school_manager.notice.list', $this->dataForView);
    }


    /**
     * 添加页面展示
     * @param NoticeRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add(NoticeRequest $request)
    {
        $dao = new NoticeInspectDao;

        $data = $dao->getInspectsBySchoolId($request->getSchoolId());

        $this->dataForView['type'] = $data;

        $this->dataForView['data'] = Notice::allType();
        $this->dataForView['js'][] = 'school_manager.notice.notice_js';

        return view('school_manager.notice.add', $this->dataForView);
    }

    /**
     * 编辑页面展示
     * @param NoticeRequest $request
     * @return string
     */
    public function load(NoticeRequest $request)
    {
        $id  = $request->get('id');
        $dao = new NoticeDao;
        return JsonBuilder::Success(['notice'=>$dao->getNoticeById($id)]);
    }

    /**
     * 保存数据
     * @param NoticeRequest $request
     * @return string
     */
    public function save(NoticeRequest $request)
    {
        $schoolId = $request->getSchoolId();
        $data              = $request->get('notice');
        $data['school_id'] = $schoolId;
        $data['user_id']   = $request->user()->id;

        if($data['type'] == Notice::TYPE_NOTICE && empty($data['image'])) {
            return JsonBuilder::Error('封面图不能为空');
        }

        if($data['type'] == Notice::TYPE_INSPECTION && empty($data['inspect_id'])) {
            return JsonBuilder::Error('检查类型不能为空');
        }


        $dao = new  NoticeDao;
        if (isset($data['id'])) {
            $result = $dao->update($data);
        } else {
            $result = $dao->add($data);
        }
        if ($result->isSuccess() && $result->getData()->status) {
            event(new NoticeSendEvent($result->getData()));
        }
        return $result->isSuccess() ? JsonBuilder::Success() : JsonBuilder::Error($result->getMessage());
    }

    /**
     * 删除 Notice media
     * @param NoticeRequest $request
     * @return string
     */
    public function delete_media(NoticeRequest $request){
        $dao = new NoticeDao();
        $result = $dao->deleteNoticeMedia($request->get('id'));
        return $result ? JsonBuilder::Success() : JsonBuilder::Error();
    }

    /**
     * @param NoticeRequest $request
     * @return string
     */
    public function delete(NoticeRequest $request){
        $dao = new NoticeDao();
        $deleted = $dao->delete($request->get('id'));
        if($deleted){
            FlashMessageBuilder::Push($request, 'success','删除成功');
        }
        else{
            FlashMessageBuilder::Push($request,'danger','删除失败, 请稍候再试');
        }
        return  redirect()->route('school_manager.notice.list');
    }
}
