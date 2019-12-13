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
                $imagePath   = $image->store('public/forum');
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
        ];

        $dao = new  ForumDao;
        $result = $dao->add($data, $resources);
        if ($result) {
            return JsonBuilder::Success('发布成功');
        } else {
            return JsonBuilder::Success('发布失败');
        }
    }

}
