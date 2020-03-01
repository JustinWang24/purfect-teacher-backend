<?php

namespace App\Http\Controllers\Api\OA;

use App\Dao\OA\WorkLogDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\MyStandardRequest;
use App\Models\OA\WorkLog;
use App\Utils\JsonBuilder;
use Carbon\Carbon;

class WorkLogController extends  Controller
{

    /**
     * 日志添加
     * @param MyStandardRequest $request
     * @return string
     */
    public function index(MyStandardRequest $request)
    {
        $user = $request->user();
        $title = $request->get('title');
        $content = $request->get('content');

        $dao = new WorkLogDao;
        $data = [
            'user_id' => $user->id,
            'title'  => $title,
            'content' => $content,
            'type' => WorkLog::TYPE_DRAFTS,
            'status' => WorkLog::STATUS_NORMAL
        ];
        $result = $dao->create($data);
        if ($result) {
            return JsonBuilder::Success('添加成功');
        } else {
            return JsonBuilder::Error('添加失败');
        }
    }

    /**
     * 日志列表
     * @param MyStandardRequest $request
     * @return string
     */
    public function workLogList(MyStandardRequest $request)
    {
        $user = $request->user();
        $type = $request->get('type');
        $keyword = $request->get('keyword');

        $dao = new WorkLogDao;
        $data = $dao->getWorkLogsByTeacherId($user->id, $type, $keyword);
        $result = [];
        foreach ($data as $key => $value) {
            $result[$key]['id'] = $value->id;
            $result[$key]['avatar'] = $value->profile->avatar;
            $result[$key]['title'] = $value->title;
            $result[$key]['content'] = $value->content;
            $result[$key]['created_at'] = $value->created_at;
        }

        return JsonBuilder::Success($result);
    }

    /**
     * 日志详情
     * @param MyStandardRequest $request
     * @return string
     */
    public function workLogInfo(MyStandardRequest $request)
    {
        $id = $request->get('id');

        $dao = new WorkLogDao;
        $data = $dao->getWorkLogsById($id);
        return JsonBuilder::Success($data);
    }

    /**
     * 发送日志
     * @param MyStandardRequest $request
     * @return string
     */
    public function workLogSend(MyStandardRequest $request)
    {
        $id = $request->get('id');
        $userId = $request->get('user_id'); // 接收人ID
        $userName = $request->get('user_name'); // 接收人

        $dao = new WorkLogDao;
        $log = $dao->getWorkLogsByIds(explode(',', $id));


        $data['update_data'] = [];
        foreach (explode(',', $id) as $key => $val) {
            $data['update_data'][$key] = [
                'id'              => $val,
                'collect_user_id' => $userId,
                'collect_user_name' => $userName,
                'type' => WorkLog::TYPE_SENT
            ];
        }

        $data['install_data'] = [];
        $userIds = explode(',', $userId);
        foreach ($log as $key => $value) {
            foreach ($userIds as $k => $val) {
                $data['install_data'][$key][$k]['user_id'] = $val;
                $data['install_data'][$key][$k]['send_user_name'] = $value->user->name;
                $data['install_data'][$key][$k]['send_user_id'] = $value->user_id;
                $data['install_data'][$key][$k]['type'] = WorkLog::TYPE_READ;
                $data['install_data'][$key][$k]['title'] = $value->title;
                $data['install_data'][$key][$k]['content'] = $value->content;
                $data['install_data'][$key][$k]['created_at'] = Carbon::parse();
            }
        }
        $result = $dao->sendLog($data);
        if ($result) {
            return JsonBuilder::Success('发送成功');
        } else {
            return JsonBuilder::Error('发送失败,请稍后再试');
        }
    }

    /**
     * 工作日志更新
     * @param MyStandardRequest $request
     * @return string
     */
    public function workLogUpdate(MyStandardRequest $request)
    {
        $id = $request->get('id');
        $data = $request->get('data');

        $dao = new WorkLogDao;

        $result = $dao->update($id, $data);
        if ($result) {
            return JsonBuilder::Success('更新成功');
        } else {
            return JsonBuilder::Error('更新失败');
        }
    }

    /**
     * 工作日志删除
     * @param MyStandardRequest $request
     * @return string
     */
    public function workLogDel(MyStandardRequest $request)
    {
        $id = $request->get('id');

        $dao = new WorkLogDao;
        $result = $dao->update($id, ['status' => WorkLog::STATUS_ERROR]);
        if ($result) {
            return JsonBuilder::Success('删除成功');
        } else {
            return JsonBuilder::Error('删除删除');
        }
    }




}
