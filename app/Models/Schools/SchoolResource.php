<?php

namespace App\Models\Schools;

use Illuminate\Database\Eloquent\Model;

class SchoolResource extends Model
{
    protected $fillable = ['school_id', 'name', 'path', 'video_path', 'type', 'size', 'format', 'width', 'height'];

    const TYPE_ALL    = 0;
    const TYPE_IMAGE  = 1;
    const TYPE_VIDEO  = 2;
    const PAGE_NUMBER = 5;

    const DRIVER_LOCAL      = 1;
    const DRIVER_ALI_YUN    = 2;
    const DRIVER_QI_NIU     = 3;

    const DEFAULT_UPLOAD_PATH_PREFIX = 'public/school/resource';    // 存放文件路径
    const DEFAULT_URL_PATH_PREFIX = '/storage/school/resource';     // 对外的

    /**
     * 转换上传路径到 url 路径
     * @param $uploadPath
     * @return string
     */
    public static function schoolResourceUploadPathToUrl($uploadPath)
    {
        // 本地图片服务
        if(env('NETWORK_DISK_DRIVER', self::DRIVER_LOCAL) === self::DRIVER_LOCAL) {
            return str_replace(self::DEFAULT_UPLOAD_PATH_PREFIX, self::DEFAULT_URL_PATH_PREFIX, $uploadPath);
        }
        return '';
    }
}
