<?php

namespace App\Dao\Notice;

use App\Models\Notices\Notice;
use App\Models\Notices\NoticeMedia;
use App\Utils\JsonBuilder;
use App\Utils\Misc\ConfigurationTool;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Support\Facades\DB;

class NoticeDao
{

    /**
     * 根据学校ID 获取通知
     * @param $where
     * @return mixed
     */
    public function getNoticeBySchoolId($where)
    {
        return Notice::where($where)->with('attachments')->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }

    public function getNoticeById($id)
    {
        return Notice::where('id', $id)->with('attachments')->first();
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
            $result = Notice::create($data);
            foreach ($data['attachments'] as $key => $val) {
                $insert = [
                    'notice_id' => $result->id,
                    'media_id'  => $val['id'],
                    'file_name' => $val['file_name'],
                    'url'       => $val['url'],
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
            $attachments = $data['attachments'];
            unset($data['attachments']);
            Notice::where('id', $data['id'])->update($data);
            foreach ($attachments as $key => $val) {
                $found = NoticeMedia::where('notice_id',$data['id'])
                    ->where('media_id',$val['id'])
                    ->first();
                if(!$found){
                    $insert = [
                        'notice_id' => $data['id'],
                        'media_id'  => $val['id'],
                        'file_name' => $val['file_name'],
                        'url'       => $val['url'],
                    ];
                    NoticeMedia::create($insert);
                }
            }
            DB::commit();
            return new MessageBag(JsonBuilder::CODE_SUCCESS,'创建成功');
        }catch (\Exception $e) {
            DB::rollBack();
            return new MessageBag(JsonBuilder::CODE_ERROR, $e->getMessage());
        }
    }


    /**
     * @param $type
     * @param $schoolId
     * @param $pageNumber
     * @param $pageSize
     * @return array
     */
    public function getNotice($type, $schoolId, $pageNumber = 0, $pageSize = ConfigurationTool::DEFAULT_PAGE_SIZE) {
        $field = ['id', 'title', 'content', 'type', 'created_at', 'inspect_id', 'image','status'];
        $map = ['type'=>$type, 'school_id'=>$schoolId, 'status'=>Notice::STATUS_PUBLISH];
        $notices = Notice::where('type',$type)->select($field)->with('attachments')
            ->skip($pageSize * $pageNumber)
            ->take($pageSize)
            ->get();
        $total = Notice::where($map)->count();
        return [
            'notices'=>$notices,
            'total'=>$total
        ];
    }

    public function deleteNoticeMedia($id){
        return NoticeMedia::where('id',$id)->delete();
    }

    public function delete($id){
        return Notice::where('id',$id)->delete();
    }
}
