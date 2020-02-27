<?php

namespace App\Dao\OA;

use App\Models\OA\WorkLog;
use App\Utils\Misc\ConfigurationTool;
use Illuminate\Support\Facades\DB;

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
        $map   = [
            ['user_id', '=', $teacherId],
            ['type', '=', $type],
            ['status', '=', WorkLog::STATUS_NORMAL]
        ];
        $where = $map;
        if (!is_null($keyword)) {
            array_push($map, ['send_user_name', 'like', "$keyword%"]);
        }

        if (!is_null($keyword)) {
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

    /**
     * 根据ID 发送日志
     * @param $id
     * @param $data
     * @return bool
     */
    public function sendLog($id, $data)
    {
        DB::beginTransaction();
        try {
            WorkLog::where('id', $id)->update($data['update_data']);
            foreach ($data['install_data'] as $key => $val) {
                WorkLog::create($val);
            }
            DB::commit();
            $result = true;
        } catch (\Exception $e) {
            DB::rollBack();
            $result = false;
        }
        return $result;
    }

    /**
     * 更新
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
        return WorkLog::where('id', $id)->update($data);
    }
}
