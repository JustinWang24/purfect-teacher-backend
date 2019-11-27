<?php

namespace App\Dao\Notice;

use App\Models\Notices\Notice;
use App\Models\Notices\NoticeMedia;
use App\Utils\JsonBuilder;
use App\Utils\Misc\ConfigurationTool;
use App\Utils\ReturnData\MessageBag;
use DB;

class NoticeDao
{

    /**
     * 根据学校ID 获取通知
     * @param $where
     * @return mixed
     */
    public function getNoticeBySchoolId($where)
    {
        return Notice::where($where)->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }


    public function getNoticeById($id)
    {
        return Notice::where('id', $id)->first();
    }



    /**
     * 添加
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        DB::beginTransaction();
        try{

            $mediaId = $data['media_id'];
            unset($data['media_id']);
            $result = Notice::create($data);
            foreach ($mediaId as $key => $val) {
                $insert = [
                    'notice_id' => $result->id,
                    'media_id'  => $val,
                    ];
                NoticeMedia::create($insert);
            }
            DB::commit();
            return new MessageBag(JsonBuilder::CODE_SUCCESS,'创建成功');
        }catch (\Exception $e) {
            DB::rollBack();
            return new MessageBag(JsonBuilder::CODE_ERROR, $e->getMessage());
        }
    }

    /**
     * 修改
     * @param $data
     * @return MessageBag
     */
    public function update($data)
    {
        DB::beginTransaction();
        try{
            $mediaId = $data['media_id'];
            unset($data['media_id']);
            $result = Notice::where('id', $data['id'])->update($data);
            foreach ($mediaId as $key => $val) {
                $insert = [
                    'notice_id' => $data['id'],
                    'media_id'  => $val,
                    ];
                NoticeMedia::where('notice_id', $data['id'])->update($insert);
            }
            DB::commit();
            return new MessageBag(JsonBuilder::CODE_SUCCESS,'创建成功');
        }catch (\Exception $e) {
            DB::rollBack();
            return new MessageBag(JsonBuilder::CODE_ERROR, $e->getMessage());
        }
    }



}
