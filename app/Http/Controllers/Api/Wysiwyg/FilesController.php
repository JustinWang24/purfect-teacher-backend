<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 21/1/20
 * Time: 5:45 PM
 */

namespace App\Http\Controllers\Api\Wysiwyg;
use App\Dao\Users\UserDao;
use App\Http\Controllers\Controller;
use App\Utils\JsonBuilder;
use App\Utils\UI\RedActor;
use Illuminate\Http\Request;

class FilesController extends Controller
{
    /**
     * 图片上传处理
     * @param Request $request
     * @return string
     */
    public function images_upload(Request $request){
        // 判断是否用户存在
        $user = (new UserDao())->getUserByIdOrUuid($request->get('uuid'));
        if($user){
            $redActor = new RedActor();
            $bag = $redActor->persistent($request, $request->get('uuid'),RedActor::TYPE_IMAGE);
            return json_encode($bag->getData());
        }
    }

    /**
     * 加载已经存在的图片列表
     * @param Request $request
     * @return string
     */
    public function images_view(Request $request){
        $redActor = new RedActor();
        $images = $redActor->loadDir($request->get('uuid'),RedActor::TYPE_IMAGE);
        return json_encode($images);
    }

    /**
     * 处理文件的上传
     * @param Request $request
     * @return string
     */
    public function files_upload(Request $request){
        // 判断是否用户存在
        $user = (new UserDao())->getUserByIdOrUuid($request->get('uuid'));
        if($user){
            $redActor = new RedActor();
            $bag = $redActor->persistent($request, $request->get('uuid'),RedActor::TYPE_FILE);
            return JsonBuilder::Success($bag->getData());
        }
    }

    /**
     * 加载已经存在的文件列表
     * @param Request $request
     * @return string
     */
    public function files_view(Request $request){
        $redActor = new RedActor();
        $files = $redActor->loadDir($request->get('uuid'),RedActor::TYPE_FILE);
        return json_encode($files);
    }
}