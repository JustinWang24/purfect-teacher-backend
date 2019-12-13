<?php


namespace App\Http\Controllers\Api\Forum;


use App\Dao\Forum\ForumCommentDao;
use App\Http\Controllers\Controller;
use App\Models\Forum\ForumComment;
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
        $result = $dao->addForumLike($forumId,$user->id);
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
        $comments = $dao->getCommentForForum($forumId);
        $result = [];
        foreach ($comments as$key => $comment) {
            $replys = $comment->reply()->get();
            $result[$key]['comment'] = $comment->toArray();
            $result[$key]['reply'] = $replys->toArray();
        }
        return JsonBuilder::Success($result);
    }
}
