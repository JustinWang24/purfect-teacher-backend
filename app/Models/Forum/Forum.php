<?php


namespace App\Models\Forum;

use App\Models\School;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    protected  $fillable = ['user_id', 'school_id', 'content', 'see_num', 'type_id', 'is_up'];

    const DRIVER_LOCAL    = 1;
    const DRIVER_ALI_YUN  = 2;
    const DRIVER_QI_NIU   = 3;

    const TYPE_IMAGE    = 1;   // 图片文件类型
    const TYPE_VIDEO    = 2;  // 视频文件类型

    const DEFAULT_UPLOAD_PATH_PREFIX = 'public/forum/';    // 存放用户文件路径
    const DEFAULT_URL_PATH_PREFIX = '/storage/forum/';     // 对外的



     /**
     * 转换上传路径到 url 路径
     * @param $uploadPath
     * @return string
     */
    public static function ForumConvertUploadPathToUrl($uploadPath)
    {
        // 本地图片服务
        if(env('NETWORK_DISK_DRIVER', self::DRIVER_LOCAL) === self::DRIVER_LOCAL) {
            return str_replace(self::DEFAULT_UPLOAD_PATH_PREFIX, self::DEFAULT_URL_PATH_PREFIX, $uploadPath);
        }
        return '';
    }

    public function user() {
        return $this->belongsTo(User::class);
    }


    public function school() {
        return $this->belongsTo(School::class);
    }

    public function countLikeNum() {
        return $this->hasMany(ForumLike::class)->count();
    }


}
