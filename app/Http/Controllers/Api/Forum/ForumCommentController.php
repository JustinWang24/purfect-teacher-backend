<?php


namespace App\Http\Controllers\Api\Forum;


use App\Dao\Forum\ForumCommentDao;
use App\Dao\Students\StudentProfileDao;
use App\Dao\Users\UserDao;
use App\Http\Controllers\Controller;
use App\Models\Forum\ForumComment;
use App\Models\Students\StudentProfile;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;

class ForumCommentController
extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * @param Request $request
     * @param $forumId
     * @return string
     */
    public function  addComment(Request $request)
    {
        $forumId = $request->get('id');
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $forumId = intval($forumId);
        //TODO 需要判断帖子是否存在，不存在不能发表评论
        $content = strip_tags($request->get('content'));
        if (!empty($content) && mb_strlen($content,"utf8")<255) {
            $dao = new ForumCommentDao();
            $data = [
                'user_id'  => $user->id,
                'forum_id' => $forumId,
                'content'  => $content,
                'school_id'  => $schoolId,
            ];
            $result = $dao->createComment($data);
            if ($result->getCode()==1000) {
                $userDao = new UserDao();
                $studentDao = new StudentProfileDao();
                $commentArr = $result->getData()->toArray();
                $commentArr['commentid'] =  $commentArr['id'];
                $commentArr['comment_pid'] =  0;
                $commentArr['comment_levid'] =  0;
                $commentArr['icheid'] =  $forumId;
                $commentArr['com_content'] =  $commentArr['content'];
                $commentArr['create_time'] =  $commentArr['created_at'];
                $commentArr['userid'] =  $commentArr['user_id'];
                $commentArr['user_nickname'] =  $userDao->getUserById($commentArr['user_id'])->name;
                $commentArr['user_pics'] =  asset($studentDao->getStudentInfoByUserId($commentArr['user_id'])->avatar);
                $commentArr['comment_praise'] =  0;
                return JsonBuilder::Success($commentArr);
            }
            return JsonBuilder::Success($result->getMessage());
        } else {
            return JsonBuilder::Error('内容不合法请重试');
        }
    }

    /**
     * @param Request $request
     * @param $commentId
     * @return string
     */
    public function  delComment(Request $request, $commentId)
    {
        $user = $request->user();
        $dao = new ForumCommentDao();
        $result = $dao->deleteComment($commentId,$user->id);
        return JsonBuilder::Success($result->getMessage());
    }

    /**
     * @param Request $request
     * @param $commentId
     * @return string
     */
    public function  addCommentReply(Request $request)
    {
        $commentId = $request->get('id');
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $commentId = intval($commentId);
        $dao = new ForumCommentDao();
        $commentObj = $dao->getComment($commentId);
        if (empty($commentObj)) {
            return JsonBuilder::Error('操作不合法请重试');
        }
        $reply = strip_tags($request->get('reply'));
        if (!empty($reply) && mb_strlen($reply,"utf8")<200) {

            $data = [
                'user_id'   => $user->id,
                'to_user_id'=> $commentObj->user_id,
                'forum_id'  => $commentObj->forum_id,
                'comment_id'=> $commentId,
                'reply'     => $reply,
                'school_id'  => $schoolId,
            ];
            $result = $dao->createCommentReply($data);
            if ($result->getCode()==1000) {
                $userDao = new UserDao();
                $studentDao = new StudentProfileDao();
                $replyArr = $result->getData()->toArray();
                $replyArr['commentid'] =  $replyArr['id'];
                $replyArr['comment_pid'] =  $commentId;
                $replyArr['comment_levid'] =  $commentId;
                $replyArr['icheid'] =  $replyArr['forum_id'];
                $replyArr['com_content'] =  $replyArr['reply'];
                $replyArr['create_time'] =  $replyArr['created_at'];
                $replyArr['userid'] =  $replyArr['user_id'];
                $replyArr['user_nickname'] =  $userDao->getUserById($replyArr['user_id'])->name;
                $replyArr['user_pics'] =  asset($studentDao->getStudentInfoByUserId($replyArr['user_id'])->avatar);
                $replyArr['comment_praise'] =  0;
                return JsonBuilder::Success($replyArr);
            }
            return JsonBuilder::Success($result->getMessage());
        } else {
            return JsonBuilder::Error('内容不合法请重试');
        }
    }

    /**
     * @param Request $request
     * @param $replyId
     * @return string
     */
    public function  delCommentReply(Request $request, $replyId)
    {
        $user = $request->user();
        $replyId = intval($replyId);
        $dao = new ForumCommentDao();
        $result = $dao->deleteCommentReply($replyId,$user->id);
        return JsonBuilder::Success($result->getMessage());
    }

    /**
     * @param Request $request
     * @param $forumId
     * @return string
     */
    public function  addLike(Request $request, $forumId)
    {
        $user = $request->user();
        $forumId = intval($forumId);
        $dao = new ForumCommentDao();
        $count = $dao->getForumLike($forumId,$user->id);
        if ($count>0)
        {
            $result = $dao->deleteForumLike($forumId,$user->id);
        } else {
            $result = $dao->addForumLike($forumId,$user->id);
        }


        return JsonBuilder::Success($result->getMessage());

    }

    /**
     * @param Request $request
     * @param $forumId
     * @return string
     */
    public function  delLike(Request $request, $forumId)
    {
        $user = $request->user();
        $forumId = intval($forumId);
        $dao = new ForumCommentDao();
        $result = $dao->deleteForumLike($forumId,$user->id);
        return JsonBuilder::Success($result->getMessage());

    }

    /**
     * @param Request $request
     * @param $forumId
     * @return string
     */
    public function getComments(Request $request) {
        $forumId = $request->get('id');
        $user = $request->user();
        $formCommentDao = new ForumCommentDao();
        $comments = $formCommentDao->getCommentForForum($forumId);
        $result = [];
        //获得评论数
        $result['info']['comment_count'] = $formCommentDao->getCountComment($forumId);
        $result['info']['comment_reply_count'] = $formCommentDao->getCountReply($forumId);
        $result['info']['comment_total'] = $result['info']['comment_count'] + $result['info']['comment_reply_count'];
        $result['info']['like_count'] =  $formCommentDao->getCountLikeForForum($forumId);
        $result['comments'] = [];
        foreach ($comments->items() as $key => $comment) {

            $replys = $comment->reply;
            $commentArr = $comment->toArray();
            $commentArr['commentid'] =  $commentArr['id'];
            $commentArr['comment_pid'] =  0;
            $commentArr['comment_levid'] =  0;
            $commentArr['icheid'] =  $commentArr['forum_id'];
            $commentArr['com_content'] =  $commentArr['content'];
            $commentArr['create_time'] =  $commentArr['created_at'];
            $commentArr['userid'] =  $commentArr['user_id'];
            $commentArr['user_nickname'] =  $comment->user->name;
            $commentArr['user_pics'] =  $comment->user->profile->avatar;
            $commentArr['reply_count'] =  $formCommentDao->getCountReplyForComment($commentArr['id']);
            $commentArr['ispraise'] =  $formCommentDao->getCommentLike($comment->id,$user->id);
            $commentArr['comment_praise'] =  $formCommentDao->getCountLikeForForum($commentArr['forum_id']);
            $result['comments'][$key]['comment'] = $commentArr;

            foreach ($replys as $k => $reply) {
                $replyArr[$k]['commentid'] = $reply->id;
                $replyArr[$k]['comment_pid'] = $commentArr['id'];
                $replyArr[$k]['comment_levid'] = $commentArr['id'];
                $replyArr[$k]['userid'] = $reply->user_id;
                $replyArr[$k]['user_pics'] = $reply->user->profile->avatar;
                $replyArr[$k]['user_nickname'] = $reply->user->name;
                $replyArr[$k]['touserid'] = $reply->to_user_id;
                $replyArr[$k]['touser_pics'] =  $reply->touser->profile->avatar;
                $replyArr[$k]['touser_nickname'] =  $reply->touser->name;
                $replyArr[$k]['icheid'] =  $commentArr['forum_id'];
                $replyArr[$k]['com_content'] =  $reply->reply;
                $replyArr[$k]['comment_praise'] =  $commentArr['comment_praise'];
                $replyArr[$k]['create_time'] =  $reply->created_at;
            }
            $result['comments'][$key]['replyList'] = $replyArr;
        }

        return JsonBuilder::Success($result);
    }
}
