<?php

namespace App\Dao\Notice;

use App\Utils\JsonBuilder;
use App\Models\Notices\Notice;
use App\Dao\NetworkDisk\MediaDao;
use Illuminate\Support\Facades\DB;
use App\Models\Notices\NoticeMedia;
use App\Utils\ReturnData\MessageBag;
use App\Utils\Misc\ConfigurationTool;
use App\Models\Notices\NoticeReadLogs;
use App\Models\Notices\NoticeOrganization;

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
            return new MessageBag(JsonBuilder::CODE_SUCCESS,'创建成功', $result);
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
            return new MessageBag(JsonBuilder::CODE_SUCCESS,'创建成功', Notice::where('id', $data['id'])->first());//?update 后如何直接返回对象
        }catch (\Exception $e) {
            DB::rollBack();
            return new MessageBag(JsonBuilder::CODE_ERROR, $e->getMessage());
        }
    }


    /**
     * @param $type
     * @param $schoolId
     * @return array
     */
    public function getNotice($type, $schoolId, $organizationId) {
        $field = ['notices.id', 'title', 'content', 'type', 'created_at',
            'inspect_id', 'image','status','notice_organizations.notice_id'];

        array_push($organizationId, 0);
        $map = ['notice_organizations.school_id'=>$schoolId, 'type'=>$type,
            'status'=>Notice::STATUS_PUBLISH];
        return NoticeOrganization::join('notices', function ($join) use ($map, $organizationId) {
            $join->on('notice_organizations.notice_id', '=', 'notices.id')
                ->where($map)->WhereIn('notice_organizations.organization_id', $organizationId);
        })->select($field)
            ->orderBy('notices.created_at', 'desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);

    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteNoticeMedia($id){
        return NoticeMedia::where('id',$id)->delete();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id){
        return Notice::where('id',$id)->delete();
    }


    /**
     * 添加阅读记录
     * @param $data
     * @return mixed
     */
    public function addReadLog($data) {
        return NoticeReadLogs::create($data);
    }


    /**
     * 发布通知
     * @param $data
     * @param $organizationIds
     * @param $file
     * @param $user
     * @return MessageBag
     */
    public function issueNotice($data, $organizationIds, $file, $user) {

        $messageBag = new MessageBag();
        $mediaDao = new MediaDao();
        // todo 需要增加后台审核功能
        $data['status'] = Notice::STATUS_PUBLISH;  // 设置为发布
        try{
            DB::beginTransaction();
            $notice = Notice::create($data);

            foreach ($organizationIds as $key => $item) {
                $organization = [
                    'school_id' => $data['school_id'],
                    'notice_id' => $notice->id,
                    'organization_id' => $item
                ];
                NoticeOrganization::create($organization);
            }

            foreach ($file as $key => $value) {
                $media = $mediaDao->upload($user,$value);
                $attachments = [
                    'notice_id' => $notice->id,
                    'media_id' => $media->id,
                    'file_name' => $media->file_name,
                    'url' => $media->url,
                ];
                NoticeMedia::create($attachments);
            }

            DB::commit();
            $messageBag->setMessage('提交成功');
            $messageBag->setData(['id'=>$notice->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            $messageBag->setCode(JsonBuilder::CODE_ERROR);
            $messageBag->setMessage('提交失败.'.$e->getMessage());
        }
        return $messageBag;
    }
}
