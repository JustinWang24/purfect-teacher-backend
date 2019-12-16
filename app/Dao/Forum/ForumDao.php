<?php

namespace App\Dao\Forum;

use App\User;
use App\Utils\JsonBuilder;
use App\Models\Forum\Forum;
use App\Models\Forum\ForumLike;
use App\Models\Forum\ForumImage;
use App\Models\Forum\ForumComment;
use Illuminate\Support\Facades\DB;
use App\Utils\ReturnData\MessageBag;
use App\Utils\Misc\ConfigurationTool;
use App\Models\Forum\ForumCommentReply;

class ForumDao
{

    /**
     * @param $data
     * @param $resources
     * @return bool
     */
    public function add($data, $resources)
    {
        DB::beginTransaction();
        try{
            $dataResult = Forum::create($data);
            foreach ($resources as $key => $val) {
                $val['forum_id'] = $dataResult->id;
                ForumImage::create($val);
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
     * 通过学校ID查询论坛
     * @param $schoolId
     * @return mixed
     */
    public function getForumBySchoolId($schoolId) {
        return Forum::where('school_id', $schoolId)
            ->orderBy('created_at','desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }

    /**
     * @param User $user
     * @param $typeId
     * @return Forum
     */
    public function select($user = null, $typeId = null)
    {
        if ($user) {
            $where = [['school_id'=> $user->getSchoolId(), 'status' => Forum::STATUS_PASS]];
        }

        if ($typeId) {
            $where = [['type_id'=> $typeId, 'status' => Forum::STATUS_PASS]];
        }
        
        return Forum::where($where)
            ->select('id', 'content', 'see_num', 'type_id', 'created_at', 'user_id')
            ->orderBy('is_up', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return Forum::find($id);
    }


    /**
     * 编辑论坛
     * @param $id
     * @param $data
     * @return mixed
     */
    public function updateForum($id, $data) {
        return Forum::where('id', $id)->update($data);
    }


    /**
     * 删除论坛
     * @param $forumId
     * @return MessageBag
     */
    public function deleteForum($forumId) {
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        DB::beginTransaction();
        try{
            // 论坛表
            Forum::where('id', $forumId)->delete();
            // 论坛点赞表
            ForumLike::where('forum_id', $forumId)->delete();
            // 论坛资源表  todo 删除视频图片资源
            ForumImage::where('forum_id', $forumId)->delete();
            // 删除评论表
            ForumComment::where('forum_id', $forumId)->delete();
            // 删除回复表
            ForumCommentReply::where('forum_id', $forumId)->delete();

            DB::commit();
            $messageBag->setCode(JsonBuilder::CODE_SUCCESS);

        }
        catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            $messageBag->setMessage('删除失败'.$msg);
        }
        return $messageBag;
    }

}
