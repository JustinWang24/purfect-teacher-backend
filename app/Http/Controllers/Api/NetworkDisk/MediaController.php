<?php

namespace App\Http\Controllers\Api\NetworkDisk;

use App\Utils\JsonBuilder;
use App\Dao\NetworkDisk\MediaDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\NetworkDisk\MediaRequest;

class MediaController extends Controller
{

    /**
     * 上传文件
     * @param MediaRequest $request
     * @return string
     * @throws \Exception
     */
    public function upload(MediaRequest $request) {

        $data = $request->getUpload();
        $mediaDao = new MediaDao();
        $re = $mediaDao->create($data);
        if($re) {
            $result = ['file'=>$re];
            return JsonBuilder::Success($result,'上传成功');
        } else {
            return JsonBuilder::Error('上传失败');
        }
    }


    /**
     * 删除
     * @param MediaRequest $request
     * @return string
     * @throws \Exception
     */
    public function delete(MediaRequest $request) {
        $uuId = $request->getUuId();
        $mediaDao = new MediaDao();
        $media = $mediaDao->getMediaByUuid($uuId);
        $re = $mediaDao->delete($media);
        if($re) {
            return JsonBuilder::Success('删除成功');
        } else {
            return JsonBuilder::Error('删除失败');
        }
    }


    /**
     * 搜索文件
     * @param MediaRequest $request
     * @return string
     */
    public function search(MediaRequest $request) {
        $keywords = $request->getKeywords();
        $user = $request->user();
        $mediaDao = new MediaDao();
        $result = $mediaDao->search($keywords,'',$user);
        return JsonBuilder::Success($result);
    }

}
