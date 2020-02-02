<?php

namespace App\Models\Schools;

use Illuminate\Database\Eloquent\Model;

class GradeResource extends Model
{
    protected $fillable = ['grade_id', 'name', 'path', 'type', 'size', 'format'];

    const DRIVER_LOCAL      = 1;
    const DRIVER_ALI_YUN    = 2;
    const DRIVER_QI_NIU     = 3;

    const DEFAULT_UPLOAD_PATH_PREFIX = 'public/grade/resource';   // 存放用户文件路径
    const DEFAULT_URL_PATH_PREFIX = '/storage/grade/resource';     // 对外的

    /**
     * 转换上传路径到 url 路径
     * @param $uploadPath
     * @return string
     */
    public static function gradeResourceUploadPathToUrl($uploadPath)
    {
        // 本地图片服务
        if(env('NETWORK_DISK_DRIVER', self::DRIVER_LOCAL) === self::DRIVER_LOCAL) {
            return str_replace(self::DEFAULT_UPLOAD_PATH_PREFIX, self::DEFAULT_URL_PATH_PREFIX, $uploadPath);
        }
        return '';
    }

     public function getPathAttribute($value)
     {
        return asset($value);
     }

}
