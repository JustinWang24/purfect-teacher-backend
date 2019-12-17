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
        $data->image_field = ['image', 'video', 'cover'];
        foreach ($data->image as $k => $item) {
                $item->image = Forum::getImageUrl($item->image);
                $item->video = Forum::getImageUrl($item->video);
                $item->cover = Forum::getImageUrl($item->cover);
            }
        unset($data['studentProfile']);
        unset($data['forumType']);
        unset($data['forumLike']);
        return JsonBuilder::Success($data, '帖子详情');

    }


}
