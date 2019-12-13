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

class ForumCommentController extends Controller
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
    public function  addComment(Request $request, $forumId)
    {
        $user = $request->user();
        $forumId = intval($forumId);
        $content = strip_tags($request->get('content'));
        if (!empty($content) && mb_strlen($content,"utf8")<200) {
            $dao = new ForumCommentDao();
            $data = [
                'user_id'  => $user->id,
                'forum_id' => $forumId,
                'content'  => $content
            ];
            $result = $dao->createComment($data);
            return JsonBuilder::Success($result->getMessage());
        } else {
            return JsonBuilder::Success('内容不合法请重试');
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
    public function  addCommentReply(Request $request, $commentId)
    {
        $user = $request->user();
        $commentId = intval($commentId);
        $dao = new ForumCommentDao();
        $commentObj = $dao->getComment($commentId);
        $reply = strip_tags($request->get('reply'));
        if (!empty($reply) && mb_strlen($reply,"utf8")<200) {

            $data = [
                'user_id'   => $user->id,
                'to_user_id'=> $commentObj->user_id,
                'forum_id'  => $commentObj->forum_id,
                'comment_id'=> $commentId,
                'reply'     => $reply
            ];
            $result = $dao->createCommentReply($data);
            return JsonBuilder::Success($result->getMessage());
        } else {
            return JsonBuilder::Success('内容不合法请重试');
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
    public function getComments(Request $request, $forumId) {
        $dao = new ForumCommentDao();
        $userDao = new UserDao();
        $studentDao = new StudentProfileDao();
        $comments = $dao->getCommentForForum($forumId);
        $result = [];
        //获得评论数
        $result['info']['comment_count'] = $dao->getCountComment($forumId);
        $result['info']['comment_reply_count'] = $dao->getCountReply($forumId);
        $result['info']['comment_total'] = $result['info']['comment_count'] + $result['info']['comment_reply_count'];
        $result['info']['like_count'] =  $dao->getCountLikeForForum($forumId);

        foreach ($comments as$key => $comment) {
            $replys = $comment->reply()->get();
            $commentArr = $comment->toArray();
            $commentArr['user_name'] =  $userDao->getUserById($commentArr['user_id'])->first()->name;
            $commentArr['user_avatar'] =  $studentDao->getStudentInfoByUserId($commentArr['user_id'])->avatar;
            $commentArr['reply_count'] =  $dao->getCountReplyForComment($commentArr['id']);

            $result['comments'][$key]['comment'] = $commentArr;
            $replyArr = $replys->toArray();
            foreach ($replyArr as $k => $reply) {
                $replyArr[$k]['to_user_name'] = $userDao->getUserById($reply['to_user_id'])->first()->name;
                $replyArr[$k]['to_user_avatar'] =  $studentDao->getStudentInfoByUserId($reply['to_user_id'])->avatar;
                $replyArr[$k]['from_user_name'] = $userDao->getUserById($reply['user_id'])->first()->name;
                $replyArr[$k]['from_user_avatar'] =  $studentDao->getStudentInfoByUserId($reply['user_id'])->avatar;
            }

            $result['comments'][$key]['reply'] = $replyArr;
        }
        return JsonBuilder::Success($result);
    }
}
