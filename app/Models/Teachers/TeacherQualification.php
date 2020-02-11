<?php

namespace App\Models\Teachers;

use App\User;
use Illuminate\Database\Eloquent\Model;

class TeacherQualification extends Model
{
    protected $fillable = ['title', 'desc', 'path', 'user_id','type','year','uploaded_by','school_id','media_id','file_name'];

    const DRIVER_LOCAL      = 1;
    const DRIVER_ALI_YUN    = 2;
    const DRIVER_QI_NIU     = 3;

    const DEFAULT_UPLOAD_PATH_PREFIX = 'public/users/';   // 存放用户文件路径
    const DEFAULT_URL_PATH_PREFIX = '/storage/users/';     // 对外的

    const TYPE_0 = '论文';
    const TYPE_1 = '课题';
    const TYPE_2 = '荣誉称号';
    const TYPE_3 = '教学';
    const TYPE_4 = '技能大赛';

    const STATUS_PENDING    = 0;   // 审核中
    const STATUS_CONFIRMED  = 1; // 已被学校采信

    /**
     * 转换上传路径到 url 路径
     * @param $uploadPath
     * @return string
     */
    public static function qualificationUploadPathToUrl($uploadPath)
    {
        // 本地图片服务
        if(env('NETWORK_DISK_DRIVER', self::DRIVER_LOCAL) === self::DRIVER_LOCAL) {
            return str_replace(self::DEFAULT_UPLOAD_PATH_PREFIX, self::DEFAULT_URL_PATH_PREFIX, $uploadPath);
        }
        return '';
    }

    public function teacher(){
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function uploadedBy(){
        return $this->belongsTo(User::class,'uploaded_by');
    }

    public function getStatusAttribute($value){
        return $value === self::STATUS_PENDING ? '审核中' : '已采信';
    }
}
