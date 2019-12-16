<?php

namespace App\Http\Controllers\Api\NetworkDisk;

use App\Dao\NetworkDisk\CategoryDao;
use App\Models\NetworkDisk\Media;
use App\Utils\JsonBuilder;
use App\Dao\NetworkDisk\MediaDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\NetworkDisk\MediaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Utils\ReturnData\MessageBag;
use App\User;
use Ramsey\Uuid\Uuid;

class MediaController extends Controller
{
    /**
     * 上传文件
     * @param MediaRequest $request
     * @return string
     * @throws \Exception
     */
    public function upload(MediaRequest $request) {
        $file = $request->getFile();
        /**
         * @var User $user
         */
        $user = $request->user();

        $path = Media::DEFAULT_UPLOAD_PATH_PREFIX.$user->id; // 上传路径
        $categoryDao = new CategoryDao();
        $category = $categoryDao->getCateInfoByUuId($request->getCategory());

        $auth = $category->isOwnedByUser($user);
        if(!$auth && !$user->isSchoolAdminOrAbove()) {
            $msgBag =  new MessageBag(JsonBuilder::CODE_ERROR,'非用户本人,不能上传');
        }else{
            try{
                $uuid = Uuid::uuid4()->toString();
                // 这里一定要使用 storeAs, 因为 Symfony 的 Mime type guesser 似乎有 bug, 一些文件总是得到 zip 的类型
                $url = $file->storeAs($path, $uuid.'.'.$file->getClientOriginalExtension()); // 上传并返回路径
                $data = [
                    'category_id' => $category->id,
                    'uuid'        => $uuid,
                    'user_id'     => $user->id, // 文件和目录的所有着, 应该一直保持一致
                    'keywords'    => $request->getKeywords(),
                    'description' => $request->getDescription(),
                    'file_name'   => $file->getClientOriginalName(),
                    'size'        => $file->getSize(),
                    'url'         => Media::ConvertUploadPathToUrl($url),
                    'type'        => Media::ParseFileType($file->getClientOriginalExtension()),
                    'driver'      => Media::DRIVER_LOCAL,
                ];
                $msgBag = new MessageBag(JsonBuilder::CODE_SUCCESS);
                $msgBag->setData($data);
            }catch (\Exception $exception){
                $msgBag = new MessageBag(JsonBuilder::CODE_ERROR, $exception->getMessage());
            }
        }

        if($msgBag->isSuccess()) {
            $mediaDao = new MediaDao();
            $result = [
                'file'=>$mediaDao->create($msgBag->getData())
            ];
            return JsonBuilder::Success($result,'上传成功');
        } else {
            return JsonBuilder::Error('上传失败: '.$msgBag->getMessage());
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
        $files = $mediaDao->search($keywords,null,$user);
        $result = [];

        foreach ($files as $file) {
            $result[] = [
                'name'=>$file->file_name.' ('.$file->getTypeText().')',
                'uuid'=>$file->uuid,
                'url'=>$file->url,
                'type'=>$file->type,
            ];
        }

        return JsonBuilder::Success(['files'=>$result]);
    }

    /**
     * 移动文件到新的目录
     * @param MediaRequest $request
     * @return string
     */
    public function move(MediaRequest $request){
        $dao = new CategoryDao();
        $toCategoryUuid = $request->getCategory();
        $category = $dao->getCateInfoByUuId($toCategoryUuid);

        $mediaDao = new MediaDao();
        $file = $mediaDao->getMediaByUuid($request->getFileUuid());

        if($file && $category){
            $file->category_id = $category->id;
            if($file->save()){
                return JsonBuilder::Success();
            }
            else{
                return JsonBuilder::Error('数据库操作失败, 请稍后再试!');
            }
        }
        else{
            return JsonBuilder::Error('找不到指定的文件或文件夹!');
        }
    }


    /**
     * 更新点击次数
     * @param MediaRequest $request
     * @return string
     */
    public function click(MediaRequest $request) {
        $uuid = $request->uuid();
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
     * 更新星标
     * @param MediaRequest $request
     * @return string
     */
    public function update_asterisk(MediaRequest $request){
        $uuid = $request->uuid();
        $mediaDao = new MediaDao();
        $user = $request->user();
        $result = $mediaDao->updAsteriskByUuidAndUser($uuid, $user);
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
        $result = [
            'size'=>[
                'use_size'=>$useSize,
                'total_size'=>Media::USER_SIZE,
                'total'=>count($request->user()->medias)
            ]
        ];
        return JsonBuilder::Success($result);
    }


    /**
     * 获取分享内容
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function shareFile(Request $request) {
        $f = $request->get('f');
        $mediaDao = new MediaDao();
        $media = $mediaDao->getMediaByUuid($f);

        $file = storage_path('app/'.Media::ConvertUrlToUploadPath($media->url));
        switch ($media->type) {
            case Media::TYPE_IMAGE :
                header('Location:'.env('APP_URL').$media->url);
                break;
            case Media::TYPE_WORD:
            case Media::TYPE_EXCEL:
            case Media::TYPE_PPT:
            case Media::TYPE_PDF:
            case Media::TYPE_VIDEO:
            case Media::TYPE_AUDIO:
            case Media::TYPE_TXT:
                return response()->download($file, $media->file_name);
                break;
            case Media::TYPE_REFERENCE :
                header('Location:http://'.$media->url);
                break;
        }
    }


}
