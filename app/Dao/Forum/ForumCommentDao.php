<?php


namespace App\Dao\Forum;


use App\Models\Forum\ForumComment;
use App\Models\Forum\ForumCommentLike;
use App\Models\Forum\ForumCommentReply;
use App\Models\Forum\ForumLike;
use App\Utils\JsonBuilder;
use App\Utils\Misc\ConfigurationTool;
use App\Utils\ReturnData\MessageBag;

class ForumCommentDao
{
    /**
     * @param $data
     * @return MessageBag
     */
    public function createComment($data) {
        $re =  ForumComment::create($data);
        if($re){
            $result = new MessageBag(JsonBuilder::CODE_SUCCESS,'添加成功');
            $result->setData($re);
            return $result;
        } else {
            return new MessageBag(JsonBuilder::CODE_ERROR,'添加失败');
        }
    }

    /**
     * @param $id
     * @param $userId
     * @return MessageBag
     */
    public function deleteComment($id, $userId) {
        $re =  ForumComment::where('id', $id)->where('user_id', $userId)->delete();
        if($re){
            return new MessageBag(JsonBuilder::CODE_SUCCESS,'删除成功');
        } else {
            return new MessageBag(JsonBuilder::CODE_ERROR,'删除失败');
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getComment($id) {
        return ForumComment::where('id', $id)->first();

    }

    /**
     * @param $data
     * @return MessageBag
     */
    public function createCommentReply($data) {
        $re =  ForumCommentReply::create($data);
        if($re){
            $result = new MessageBag(JsonBuilder::CODE_SUCCESS,'添加成功');
            $result->setData($re);
            return $result;
        } else {
            return new MessageBag(JsonBuilder::CODE_ERROR,'添加失败');
        }
    }

    /**
     * @param $id
     * @param $userId
     * @return MessageBag
     */
    public function deleteCommentReply($id, $userId) {
        $re =  ForumCommentReply::where('id', $id)->where('user_id', $userId)->delete();
        if($re){
            return new MessageBag(JsonBuilder::CODE_SUCCESS,'删除成功');
        } else {
            return new MessageBag(JsonBuilder::CODE_ERROR,'删除失败');
        }
    }

    /**
     * @param $forumId
     * @param $userId
     * @return MessageBag
     */
    public function addForumLike($forumId,$userId) {
        try{
            $re =  ForumLike::create([
                'forum_id' => $forumId,
                'user_id'  => $userId
            ]);
            if($re){
                return new MessageBag(JsonBuilder::CODE_SUCCESS,'添加成功');
            }
        }catch (\Exception $exception) {
            return new MessageBag(JsonBuilder::CODE_ERROR,'添加失败');
        }
    }

    /**
     * @param $forumId
     * @param $userId
     * @return MessageBag
     */
    public function deleteForumLike($forumId,$userId) {
        $re =  ForumLike::where('forum_id', $forumId)->where('user_id', $userId)->delete();
        if($re){
            return new MessageBag(JsonBuilder::CODE_SUCCESS,'删除成功');
        } else {
            return new MessageBag(JsonBuilder::CODE_ERROR,'删除失败');
        }
    }

    public function getForumLike($forumId,$userId)
    {
        return ForumLike::where('forum_id', $forumId)->where('user_id', $userId)->count();
    }
    /**
     * @param $forumId
     * @param int $pageSize
     * @return mixed
     */
    public function getCommentForForum($forumId, $pageSize=ConfigurationTool::DEFAULT_PAGE_SIZE)
    {
        return ForumComment::where('forum_id', $forumId)->where('status', 1)->orderBy('id','DESC')->simplePaginate($pageSize);
    }

    /**
     * @param $forumId
     * @return mixed
     */
    public function getCountComment($forumId)
    {
        return ForumComment::where('forum_id', $forumId)->where('status', 1)->count();
    }

    /**
     * @param $forumId
     * @return mixed
     */
    public function getCountReply($forumId)
    {
        return ForumCommentReply::where('forum_id', $forumId)->where('status', 1)->count();
    }

    /**
     * @param $commentId
     * @return mixed
     */
    public function getCountReplyForComment($commentId)
    {
        return ForumCommentReply::where('comment_id', $commentId)->where('status', 1)->count();
    }

    public function getCountLikeForForum($forumId)
    {
        return ForumLike::where('forum_id', $forumId)->count();
    }
    /**
     * @param $forumId
     * @param $userId
     * @return MessageBag
     */
    public function addCommentLike($commentId,$userId) {
        try{
            $re =  ForumCommentLike::create([
                'comment_id' => $commentId,
                'user_id'  => $userId
            ]);
            if($re){
                return new MessageBag(JsonBuilder::CODE_SUCCESS,'添加成功');
            }
        }catch (\Exception $exception) {
            return new MessageBag(JsonBuilder::CODE_ERROR,'添加失败');
        }
    }

    /**
     * @param $forumId
     * @param $userId
     * @return MessageBag
     */
    public function deleteCommentLike($commentId,$userId) {
        $re =  ForumCommentLike::where('comment_id', $commentId)->where('user_id', $userId)->delete();
        if($re){
            return new MessageBag(JsonBuilder::CODE_SUCCESS,'删除成功');
        } else {
            return new MessageBag(JsonBuilder::CODE_ERROR,'删除失败');
        }
    }

    public function getCommentLike($commentId,$userId)
    {
        return ForumCommentLike::where('comment_id', $commentId)->where('user_id', $userId)->count();
    }
    public function getCountLikeForForumComment($commentId)
    {
        return ForumLike::where('comment_id', $commentId)->count();
    }
}
