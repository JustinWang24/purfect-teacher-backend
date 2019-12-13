<?php


namespace App\Http\Controllers\Api\Forum;

use App\Dao\Forum\ForumDao;
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
            foreach ($images as $image) {
                $imagePath            = $image->store('public/forum');
                $resources[]['image'] = Forum::ForumConvertUploadPathToUrl($imagePath);
            }
        } elseif ($type == Forum::TYPE_VIDEO) {
            $video = $request->file('video')->store('public/forum');
            $cover = $request->file('cover')->store('public/forum');
            // 只有一个视频
            $resources[0]['video'] = Forum::ForumConvertUploadPathToUrl($video);
            $resources[0]['cover'] = Forum::ForumConvertUploadPathToUrl($cover);
        }
        $data = [
            'school_id' => $user->getSchoolId(),
            'user_id'   => $user->getId(),
            'content'   => $content,
            'type_id'   => 1,
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

        foreach ($lists as $key => $list) {
            $lists[$key]['type']        = $list->forumType->title;
            $lists[$key]['comment_num'] = $list->forumComment->count();
            $lists[$key]['like_num']    = $list->forumLike->count();
            $lists[$key]['avatar']      = Forum::getImageUrl($list->studentProfile->avatar);
            $lists[$key]['user_name']   = $list->studentProfile->user->name;
            unset($lists[$key]['forumType']);
            unset($lists[$key]['type_id']);
            unset($lists[$key]['forumComment']);
            unset($lists[$key]['forumLike']);
            unset($lists[$key]['studentProfile']);
        }

        $data = pageReturn($lists);
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

        $data['avatar']    = Forum::getImageUrl($data->studentProfile->avatar);
        $data['user_name'] = $data->studentProfile->user->name;
        $data['type']      = $data->forumType->title;
        $data['like_num']  = $data->forumLike->count();
        unset($data['studentProfile']);
        unset($data['forumType']);
        unset($data['forumLike']);
        return JsonBuilder::Success($data, '帖子详情');

    }


}
