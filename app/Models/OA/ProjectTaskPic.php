<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/1/13
 * Time: 下午2:43
 */

namespace App\Models\OA;


use Illuminate\Database\Eloquent\Model;

class ProjectTaskPic extends Model
{
    protected $table = 'oa_project_task_pics';

    protected $updated_at = false;

    const DEFAULT_UPLOAD_PATH_PREFIX = 'public/users/';   // 存放用户文件路径
    const DEFAULT_URL_PATH_PREFIX = '/storage/users/';     // 对外的


    protected $fillable = ['task_id', 'task_member_id', 'url', 'user_id'];

    const DRIVER_LOCAL      = 1;
    const DRIVER_ALI_YUN    = 2;
    const DRIVER_QI_NIU     = 3;


    /**
     * 转换上传路径到 url 路径
     * @param $uploadPath
     * @return string
     */
    public static function ConvertUploadPathToUrl($uploadPath)
    {
        // 本地图片服务
        if(env('NETWORK_DISK_DRIVER', self::DRIVER_LOCAL) === self::DRIVER_LOCAL) {
            return str_replace(self::DEFAULT_UPLOAD_PATH_PREFIX, self::DEFAULT_URL_PATH_PREFIX, $uploadPath);
        }
        return '';
    }

}