<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 16/1/20
 * Time: 3:27 PM
 */

namespace App\Utils\UI;


use Illuminate\Support\Facades\Storage;

class RedActor
{
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
}