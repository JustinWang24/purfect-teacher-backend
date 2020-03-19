<?php

namespace App\Dao\OA;

use App\Events\SystemNotification\OaMessageEvent;
use App\Models\OA\InternalMessage;
use App\Models\OA\InternalMessageFile;
use App\Utils\Misc\ConfigurationTool;
use Illuminate\Support\Facades\DB;

class InternalMessageDao
{
    /**
     * 添加信件
     * @param $data
     * @param $files
     * @return bool
     */
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

            if ($data['type'] == InternalMessage::TYPE_SENT) {
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
					$collData['is_file']           = $data['is_file'];
					$newMessage = InternalMessage::create($collData);

					event(new OaMessageEvent($value, $newMessage->id));
                }
            }

            if ($data['is_file'] == InternalMessage::IS_FILE) {
                foreach ($files as $key => $val) {
                    $imageData['path']  = $val['path'];
                    $imageData['name']  = $val['name'];
                    $imageData['type'] = $val['type'];
                    $imageData['size'] = $val['size'];
                    $imageData['message_id'] = $message->id;
                    InternalMessageFile::create($imageData);
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
     * 更新信件
     * @param $id
     * @param $data
     * @param $files
     * @return bool
     */
    public function update($id, $data, $files)
    {

        DB::beginTransaction();
        try{

            $updateData = [
                'user_id'           => $data['user_id'],
                'collect_user_id'   => $data['collect_user_id'],
                'collect_user_name' => $data['collect_user_name'],
                'title'             => $data['title'],
                'content'           => $data['content'],
                'type'              => $data['type'],
                'is_relay'          => $data['is_relay'],
                'is_file'           => $data['is_file'],
            ];

            InternalMessage::where('id', $id)->update($updateData);
            $message = InternalMessage::find($id);

            // 删除之前附件数据
            InternalMessageFile::where('message_id', $message->message_id)->delete();
            $messageIds = $message->id; // 用于转发

            if ($data['is_relay'] == InternalMessage::IS_RELAY) {
                $relay = $this->getInternalMessageById($data['relay_id']);
                $messageIds = $message->id.','.$relay['message_id'];
            }

            $this->updateMessage($message->id, ['message_id' => $messageIds]); // 修改转发字段 用于转发

            if ($data['type'] == InternalMessage::TYPE_SENT) {
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
                    $collData['is_file']           = $data['is_file'];
                    $newMessage = InternalMessage::create($collData);

                    event(new OaMessageEvent($value, $newMessage->id));
                }
            }

            if ($data['is_file'] == InternalMessage::IS_FILE) {
                foreach ($files as $key => $val) {
                    $imageData['path']       = $val['path'];
                    $imageData['name']       = $val['name'];
                    $imageData['type']       = $val['type'];
                    $imageData['size']       = $val['size'];
                    $imageData['message_id'] = $message->id;
                    InternalMessageFile::create($imageData);
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
     * @param $where
     * @return mixed
     */
    public function getInternalMessageByUserId($userId, $where)
    {
        return InternalMessage::where($where)
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
