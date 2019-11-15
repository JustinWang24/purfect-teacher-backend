<?php

namespace App\Dao\NetworkDisk;

use App\Models\NetworkDisk\Media;
use App\Models\NetworkDisk\Category;
use Illuminate\Support\Facades\Storage;
class MediaDao {

     private $fieldsToLoad;

    public function __construct($fieldsToLoad = null)
    {
        if($fieldsToLoad){
            $this->fieldsToLoad = $fieldsToLoad;
        }else{
            $this->fieldsToLoad = [
                'id',         // 文件的 UUID
                'uuid',         // 文件的 UUID
                'user_id',      // 文件关联的用户的 ID
                'type',         // 文件的类型
                'category_id',  // 文件的归类
                'size',         // 文件大小
                'period',       // 如果是视频或音频文件, 播放时长
                'url',          // 文件的下载 URL
                'created_at',   // 文件的创建时间
                'keywords',     // 文件关键字
                'description',  // 文件内容介绍
                'file_name',    // 原始的文件名
                'driver',       // 文件保存在哪里
            ];
        }
    }


    /**
     * 创建
     * @param $data
     * @return mixed
     */
    public function create($data) {
        return Media::create($data);
    }


    /**
     * 根据目录ID获取媒体
     * @param $categoryId
     * @return mixed
     */
    public function getMediaByCategoryId($categoryId) {
        return Media::where('category_id',$categoryId)->get();
    }


    /**
     * 根据ID获取
     * @param int $id
     * @return Media
     */
    public function getMediaById($id)
    {
        return Media::GetById($id, $this->fieldsToLoad);
    }


    /**
     * 根据uuid获取
     * @param $uuid
     * @return mixed
     */
    public function getMediaByUuid($uuid) {
        return Media::GetByUuid($uuid);
    }

    /**
     * 删除
     * @param Media $media
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Media $media) {

        $media->delete();
        return $this->deleteFile($media->url);
    }


    /**
     * 删除文件资源
     * @param $file
     * @return mixed
     */
    public function deleteFile($file){
        return Storage::delete($file);
    }


    /**
     * 删除文件和文件资源
     * @param Category $category
     */
    public function deleteMediasByCategory(Category $category){
        foreach ($category->medias as $media) {
            /**
             * @var Media $media
             */
            $this->deleteFile($media->url);
        }
        Media::where('category_id',$category->id)->delete();
    }


    /**
     * 根据提交的关键字来搜索文件
     * @param $keywords
     * @param Category|int|null $category
     * @param User|int|null $user
     * @return array
     */
    public function search($keywords, $category = null, $user = null){
        $result = ['files'=>[]];

        $where = [];
        if($category){
            if(is_object($category)){
                $category = $category->id;
            }
            $where[] = ['category_id','=',$category];
        }
        if($user){
            if(is_object($user)){
                $user = $user->id;
            }
            $where[] = ['user_id','=',$user];
        }
        $where[] = ['keywords','like',$keywords.'%'];

        $files = Media::select('file_name', 'uuid', 'url', 'type', 'size', 'period')
            ->where($where)->get();

        if(count($files)){
            $result['files'] = $files->toArray();
        }

        return $result;
    }
}
