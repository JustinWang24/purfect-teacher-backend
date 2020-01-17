<?php

namespace App\Dao\OA;

use App\Models\OA\WorkLog;
use App\Utils\Misc\ConfigurationTool;

class WorkLogDao
{
    /**
     * 添加
     * @param  $data
     * @return WorkLog
     */
    public function create($data)
    {
        return WorkLog::create($data);
    }

    /**
     * 根据 教师ID 获取
     * @param $teacherId
     * @param $type
     * @param null $keyword
     * @return WorkLog
     */
    public function getWorkLogsByTeacherId($teacherId, $type, $keyword = null)
    {
        $map = [
            ['user_id' ,'=', $teacherId],
            ['type', '=', $type],
            ['status', '=', WorkLog::STATUS_NORMAL]
        ];
        $where = $map;
        if(!is_null($keyword)) {
            array_push($map, ['user_name', 'like', "$keyword%"]);
        }

        if(!is_null($keyword)) {
            array_push($where, ['title', 'like', "$keyword%"]);
        }

        return WorkLog::where($map)->orWhere($where)->orderBy('created_at', 'desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);

    }

    /**
     * 根据ID 获取详情
     * @param $id
     * @return WorkLog
     */
    public function getWorkLogsById($id)
    {
        return WorkLog::find($id);
    }
}
