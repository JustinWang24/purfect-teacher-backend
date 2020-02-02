<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 16/1/20
 * Time: 3:27 PM
 */

namespace App\Utils\UI;


use App\Utils\ReturnData\IMessageBag;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class RedActor
{
    // 专门开辟一个空间, 是老师保存课件的地方
    const WYSIWYG_ROOT = 'app/public/wysiwyg';
    const TYPE_IMAGE = 'image';
    const TYPE_FILE = 'file';

    /**
     * 获取所有的 redactor 的插件 plugin 目录名
     * @return array
     */
    public function allPlugIns(){
        $pluginPath = resource_path('redactor/_plugins');
        $folders = scandir($pluginPath);
        $result =  array_filter($folders, function ($dir){
            if($dir!='.' && $dir !== '..'){
                return $dir;
            }
        });
        return $result;
    }

    /**
     * 专门用来保存富文本编辑器上传文件的空间
     * @param $userUuid
     * @param $type
     * @return string
     */
    public function getWysiwygRoot($userUuid, $type){
        return storage_path(self::WYSIWYG_ROOT.DIRECTORY_SEPARATOR.$userUuid.DIRECTORY_SEPARATOR.$type);
    }

    /**
     * 把文件的绝对路径转换成 url
     * @param $absPath
     * @return string
     */
    public function convertToUrl($absPath){
        return str_replace(
            'public',
            'storage',
            $absPath);
    }

    /**
     * @param Request $request
     * @param $userUuid
     * @param string $type
     * @return IMessageBag
     */
    public function persistent($request, $userUuid, $type){
        $rootFolderPath = $this->getWysiwygRoot($userUuid, $type);
        if(!file_exists($rootFolderPath)){
            mkdir($rootFolderPath, 0777, true);
        }
        $files = $request->file('file');
        $bag = new MessageBag();
        $paths = [];
        foreach ($files as $file) {
            /**
             * @var UploadedFile $file
             */
            $path = $file->storeAs('public/wysiwyg/'.$userUuid.DIRECTORY_SEPARATOR.$type, $file->getClientOriginalName());
            $paths[] = $this->convertToUrl('/'.$path);
        }
        $data = [];
        if(count($paths) === 1){
            $data[] = [
                'file'=>[
                    'id'=>1,
                    'url'=>$paths[0]
                ]
            ];
        }else{
            foreach ($paths as $idx => $path) {
                $data[] = [
                    'file-'.($idx+1)=>[
                        'id'=>$idx+1,
                        'url'=>$path
                    ]
                ];
            }
        }
        $bag->setData($data);
        return $bag;
    }

    /**
     * 加载指定的类型的所有文件
     * @param $userUuid
     * @param $type
     * @return array
     */
    public function loadDir($userUuid, $type){
        $dir = $this->getWysiwygRoot($userUuid, $type);
        if(!file_exists($dir)){
            // 如果指定的目录不存在， 就创建该目录
            mkdir($dir, 0777, true);
        }
        $list = scandir($dir);
        if($type === self::TYPE_IMAGE){
            return $this->_images($list, $userUuid);
        }
        else{
            return $this->_files($list, $userUuid);
        }
    }

    private function _images($list, $uuid){
        $images = [];
        foreach ($list as $idx => $item) {
            if($item !== '.' && $item !== '..'){
                $images[] = [
                    'thumb'=>$this->_buildUrl($uuid, self::TYPE_IMAGE, $item),
                    'url'=>$this->_buildUrl($uuid, self::TYPE_IMAGE, $item),
                    'id'=>$idx,
                    'title'=>$item,
                ];
            }
        }
        return $images;
    }

    private function _files($list, $uuid){
        $images = [];
        foreach ($list as $idx => $item) {
            if($item !== '.' && $item !== '..'){
                $images[] = [
                    'size'=>0,
                    'url'=>$this->_buildUrl($uuid, self::TYPE_IMAGE, $item),
                    'id'=>$idx,
                    'title'=>$item,
                    'name'=>$item,
                ];
            }
        }
        return $images;
    }

    /**
     * 确保生成正确的 URL
     * @param $uuid
     * @param $type
     * @param $name
     * @return string
     */
    private function _buildUrl($uuid, $type, $name){
        return asset('/storage/wysiwyg/' . $uuid . '/' . $type .'/'.$name);
    }
}