<?php

namespace App\Http\Controllers\Api\NetworkDisk;

use App\Models\NetworkDisk\Media;
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


    /**
     * 更新点击次数
     * @param MediaRequest $request
     * @return string
     */
    public function click(MediaRequest $request) {
        $uuid = $request->getUuId();
        $mediaDao = new MediaDao();
        $user = $request->user();
        $result = $mediaDao->updClickByUuidAndUser($uuid, $user);
        if($result) {
            return JsonBuilder::Success('更新成功');
        } else {
            return JsonBuilder::Error('更新失败');
        }
    }



    /**
     * 最近上传和浏览
     * @param MediaRequest $request
     * @return string
     */
    public function latelyUploadingAndBrowse(MediaRequest $request) {
        $mediaDao = new MediaDao();
        $user = $request->user();
        // 最近添加
        $uploading = $mediaDao->getMediaList($user, 0, 3, 'created_at', 'desc');
        // 最难更新
        $browse= $mediaDao->getMediaList($user, 0, 3, 'updated_at', 'desc');

        $data = ['uploading'=>$uploading, 'browse'=>$browse];
        return JsonBuilder::Success($data);
    }


    /**
     * 判断是否可以上传  is_upload 0不可以上传  1可以上传
     * @param MediaRequest $request
     * @return string
     */
    public function judgeIsUpload(MediaRequest $request) {
        $size = $request->getSize();
        $mediaDao = new MediaDao();
        $useSize = $mediaDao->getUseSize($request->user());
        $totalSize = Media::USER_SIZE;
        $result =  $totalSize - $useSize - $size;
        $size = [
            'total_size'=>ceil($totalSize/1024/1024).'G',
            'use_size'=>ceil($useSize/1024).'M',
            'is_upload'=>Media::MAY_UPLOAD
        ];
        if($result < 0 ){
            $size['is_upload'] = Media::MAY_NOT_UPLOAD;
        }

        $data['size'] = $size;
        return JsonBuilder::Success($data);
    }


    /**
     * 获取文件详情
     * @param MediaRequest $request
     * @return string
     */
    public function getMediaInfo(MediaRequest $request) {
        $uuId = $request->getUuId();
        $mediaDao = new MediaDao();
        $result = $mediaDao->getMediaByUuid($uuId);
        if(!empty($result)) {
            return JsonBuilder::Success(['media'=>$result]);
        } else {
            return JsonBuilder::Error('该信息不存在');
        }
    }


    /**
     * 获取用户磁盘空间
     * @param MediaRequest $request
     * @return string
     */
    public function getNetWorkDiskSize(MediaRequest $request) {
        $mediaDao = new MediaDao();
        $useSize = $mediaDao->getUseSize($request->user());
        $useSize = ceil($useSize/1024);
        $total = ceil(Media::USER_SIZE/1024/1024);
        $result = ['size'=>['use_size'=>$useSize.'M','total_size'=>$total.'G']];
        return JsonBuilder::Success($result);
    }


}
