<?php


namespace App\Http\Controllers\Api\Forum;

use App\Dao\Forum\ForumCommentDao;
use App\Dao\Forum\ForumCommunityDao;
use App\Dao\Forum\ForumDao;
use App\Dao\Students\StudentProfileDao;
use App\Dao\Users\UserDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Forum\ForumRequest;
use App\Models\Forum\Forum;
use App\User;
use App\Utils\JsonBuilder;

class ForumController extends Controller
{

    /**
     * 发帖
     * @param ForumRequest $request
     * @return string
     */
    public function index(ForumRequest $request)
    {
        /**
         * @var User $user
         */
        $user    = $request->user();
        $content = $request->get('content');
        $type    = $request->get('type');
        $images  = $request->file('image');

        $resources = [];
        if ($type == Forum::TYPE_IMAGE) {
            foreach ($images as $key => $image) {
                $imagePath            = $image->store('public/forum');
                $resources[]['image'] = Forum::ForumConvertUploadPathToUrl($imagePath);
                $resources[$key]['type'] = Forum::TYPE_IMAGE;
            }
        } elseif ($type == Forum::TYPE_VIDEO) {
            $video = $request->file('video')->store('public/forum');
            $cover = $request->file('cover')->store('public/forum');
            // 只有一个视频
            $resources[0]['video'] = Forum::ForumConvertUploadPathToUrl($video);
            $resources[0]['cover'] = Forum::ForumConvertUploadPathToUrl($cover);
            $resources[0]['type']  = Forum::TYPE_VIDEO;
        }
        $data = [
            'school_id' => $user->getSchoolId(),
            'user_id'   => $user->getId(),
            'content'   => $content,
            'type_id'   => 2, // 校园互动类型
        ];

        $dao    = new  ForumDao;
        $result = $dao->add($data, $resources);
        if ($result) {
            return JsonBuilder::Success('发布成功');
        } else {
            return JsonBuilder::Success('发布失败');
        }
    }

    /**
     * 帖子列表
     * @param ForumRequest $request
     * @return string
     */
    public function list(ForumRequest $request)
    {
        $user = $request->user();

        $dao = new ForumDao;

        $lists = $dao->select($user);

        foreach ($lists as $key => $val) {

            $lists[$key]['type']        = $val->forumType->title;
            $lists[$key]['comment_num'] = $val->forumComment->count();
            $lists[$key]['like_num']    = $val->forumLike->count();
            $lists[$key]['avatar']      = $val->studentProfile->avatar;
            $lists[$key]['user_name']   = $val->studentProfile->user->name;


            $val->image_field = ['image'];
            $image = $val->image;
            foreach ($image as $k => $item) {
                $item->image;
            }

            $val->image_field = ['video', 'cover'];
            $video = $val->video;
            $lists[$key]['video_path'] = $video['video'];
            $lists[$key]['cover'] = $video['cover'];

            unset($lists[$key]['video']);
            unset($lists[$key]['forumType']);
            unset($lists[$key]['type_id']);
            unset($lists[$key]['forumComment']);
            unset($lists[$key]['forumLike']);
            unset($lists[$key]['studentProfile']);
        }

        $data = pageReturn($lists);

        //获取社群列表
        $schoolId = $user->getSchoolId();
        $communityDao = new ForumCommunityDao();
        $userDao = new UserDao();
        $studentDao = new StudentProfileDao();
        $result = $communityDao->getCommunities($schoolId,2);

        $communityData = [];
        foreach ($result as $key => $community) {
            $communityArr = $community->toArray();
            $communityArr = $communityDao->getPicUrl($communityArr);
            $communityArr['user_name'] = $userDao->getUserById($communityArr['user_id'])->name;
            $communityArr['user_avatar'] = asset($studentDao->getStudentInfoByUserId($communityArr['user_id'])->avatar);
            $communityData[$key] = $communityArr;
            $communityData[$key]['member'] = $community->member()->count();

        }

        $data['communities'] = $communityData;

        return JsonBuilder::Success($data, '帖子列表');
    }

    /**
     * 帖子详情
     * @param ForumRequest $request
     * @return string
     */
    public function details(ForumRequest $request)
    {
        $id   = $request->get('id');
        $dao  = new ForumDao;
        $data = $dao->find($id);

        $data['avatar']    = $data->studentProfile->avatar;
        $data['user_name'] = $data->studentProfile->user->name;
        $data['type']      = $data->forumType->title;
        $data['like_num']  = $data->forumLike->count();
        $data->image_field = ['image'];
        foreach ($data->image as $k => $item) {
            $item->image = $item->image;
        }

        $data->image_field = ['video', 'cover'];
        $video = $data->video;
        $data['video_path'] = $video['video'];
        $data['cover']     = $video['cover'];

        unset($data['studentProfile']);
        unset($data['forumType']);
        unset($data['forumLike']);

        unset($data['video']);

        //帖子中加入评论

        $user = $request->user();
        $formCommentDao = new ForumCommentDao();
        $userDao = new UserDao();
        $studentDao = new StudentProfileDao();
        $comments = $formCommentDao->getCommentForForum($id);
        $result = [];
        //获得评论数
        $result['info']['comment_count'] = $formCommentDao->getCountComment($id);
        $result['info']['comment_reply_count'] = $formCommentDao->getCountReply($id);
        $result['info']['comment_total'] = $result['info']['comment_count'] + $result['info']['comment_reply_count'];
        $result['info']['like_count'] =  $formCommentDao->getCountLikeForForum($id);
        $result['comments'] = [];
        foreach ($comments->items() as $key => $comment) {
            $replys = $comment->reply()->get();
            $commentArr = $comment->toArray();
            $commentArr['commentid'] =  $commentArr['id'];
            $commentArr['comment_pid'] =  0;
            $commentArr['comment_levid'] =  0;
            $commentArr['icheid'] =  $commentArr['forum_id'];
            $commentArr['com_content'] =  $commentArr['content'];
            $commentArr['create_time'] =  $commentArr['created_at'];
            $commentArr['userid'] =  $commentArr['user_id'];
            $commentArr['user_nickname'] =  $userDao->getUserById($commentArr['user_id'])->first()->name;
            $commentArr['user_pics'] =  asset($studentDao->getStudentInfoByUserId($commentArr['user_id'])->avatar);
            $commentArr['reply_count'] =  $formCommentDao->getCountReplyForComment($commentArr['id']);
            $commentArr['ispraise'] =  $formCommentDao->getCommentLike($comment->id,$user->id);
            $commentArr['comment_praise'] =  $formCommentDao->getCountLikeForForum($commentArr['forum_id']);
            $result['comments'][$key]['comment'] = $commentArr;
            $replyArr = $replys->toArray();
            foreach ($replyArr as $k => $reply) {
                $replyArr[$k]['commentid'] = $reply->id;
                $replyArr[$k]['comment_pid'] = $comment->id;
                $replyArr[$k]['comment_levid'] = $comment->id;
                $replyArr[$k]['userid'] = $reply['user_id'];
                $replyArr[$k]['user_pics'] = asset($studentDao->getStudentInfoByUserId($reply['user_id'])->avatar);
                $replyArr[$k]['user_nickname'] = $userDao->getUserById($reply['user_id'])->first()->name;
                $replyArr[$k]['touserid'] = $reply['to_user_id'];
                $replyArr[$k]['touser_pics'] =  asset($studentDao->getStudentInfoByUserId($reply['to_user_id'])->avatar);
                $replyArr[$k]['touser_nickname'] =  $userDao->getUserById($reply['to_user_id'])->first()->name;
                $replyArr[$k]['icheid'] =  $commentArr['forum_id'];
                $replyArr[$k]['com_content'] =  $reply['reply'];
                $replyArr[$k]['comment_praise'] =  $commentArr['comment_praise'];
                $replyArr[$k]['create_time'] =  $reply['create_at'];
            }

            $result['comments'][$key]['replyList'] = $replyArr;
        }

        $data['commentList'] = $result['comments'];


        return JsonBuilder::Success($data, '帖子详情');

    }


}
