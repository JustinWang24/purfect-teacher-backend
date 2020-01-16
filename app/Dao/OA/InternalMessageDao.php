<?php

namespace App\Dao\OA;

use App\Models\OA\InternalMessage;
use App\Models\OA\InternalMessageFile;
use App\Utils\Misc\ConfigurationTool;
use Illuminate\Support\Facades\DB;

class InternalMessageDao
{

    public function create($data, $files)
    {
        DB::beginTransaction();
        try{

            $message = InternalMessage::create($data);
                
            $messageIds = $message->id; // 用于转发

            if ($data['is_relay'] == InternalMessage::IS_RELAY) {
                $relay = $this->getInternalMessageById($data['relay_id']);
                $messageIds = $message->id.','.$relay['message_id'];
            }
                
            $this->updateMessage($message->id, ['message_id' => $messageIds]); // 修改转发字段 用于转发

            // 处理收件人数据
            $collId = explode(',', $data['collect_user_id']);
            $collData = [];
            foreach ($collId as $key => $value) {
                $collData['user_id']           = $data['user_id'];
                $collData['collect_user_id']   = $value;
                $collData['collect_user_name'] = $data['collect_user_name'];
                $collData['title']             = $data['title'];
                $collData['content']           = $data['content'];
                $collData['message_id']        = $messageIds;
                $collData['type']              = InternalMessage::TYPE_UNREAD;
                $collData['is_relay']          = $data['is_relay'];
                InternalMessage::create($collData);
            }

            if ($files) {
                foreach ($files as $key => $val) {
                    $val['message_id'] = $message->id;
                    InternalMessageFile::create($val);
                }
            }
            DB::commit();
            $result = true;
        }catch (\Exception $e) {
            DB::rollBack();
            $result = false;
        }

        return $result;
    }

    /**
     * 根据用户ID 获取
     * @param $userId
     * @param $type
     * @return mixed
     */
    public function getInternalMessageByUserId($userId, $type)
    {
        return InternalMessage::where('user_id', $userId)
            ->where('type', $type)
            ->where('status', InternalMessage::STATUS_NORMAL)
            ->orderBy('created_at', 'desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }

    /**
     * 根据 id 获取
     * @param $id
     * @return mixed
     */
    public function getInternalMessageById($id)
    {
        return InternalMessage::find($id);
    }

    /**
     * 获取转发的消息
     * @param $ids
     * @return mixed
     */
    public function getForwardMessageByIds($ids)
    {
        return InternalMessage::whereIn('id', $ids)->get();
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function updateMessage($id, $data)
    {
        return InternalMessage::where('id', $id)->update($data);
    }

}
