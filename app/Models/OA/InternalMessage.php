<?php

namespace App\Models\OA;

use App\Models\Teachers\TeacherProfile;
use App\User;
use Illuminate\Database\Eloquent\Model;

class InternalMessage extends Model
{
    protected $table = 'oa_internal_messages';

    protected $fillable = ['user_id', 'collect_user_id', 'collect_user_name',
                           'title', 'content', 'type', 'is_relay', 'message_id',
                           'status'
    ];

    const DRIVER_LOCAL      = 1;
    const DRIVER_ALI_YUN    = 2;
    const DRIVER_QI_NIU     = 3;

    const DEFAULT_UPLOAD_PATH_PREFIX = 'public/internal';   // 存放用户文件路径
    const DEFAULT_URL_PATH_PREFIX = '/storage/internal';     // 对外的

    const TYPE_UNREAD = 1;  // 未读
    const TYPE_READ   = 2;  // 已读
    const TYPE_SENT   = 3;  // 已发送
    const TYPE_DRAFTS = 4;  // 草稿箱

    const IS_RELAY = 1; // 转发
    const NO_RELAY = 0; // 未转发

    const IS_FILE = 1; // 有附件
    const NO_FILE = 0; // 没有附件

    const STATUS_ERROR = 0;  // 不显示
    const STATUS_NORMAL = 1; // 显示

    const DELETE = 1;  // 删除
    const UPDATE = 2;  // 更新

    /**
     * 转换上传路径到 url 路径
     * @param $uploadPath
     * @return string
     */
    public static function internalMessageUploadPathToUrl($uploadPath)
    {
        // 本地图片服务
        if(env('NETWORK_DISK_DRIVER', self::DRIVER_LOCAL) === self::DRIVER_LOCAL) {
            return str_replace(self::DEFAULT_UPLOAD_PATH_PREFIX, self::DEFAULT_URL_PATH_PREFIX, $uploadPath);
        }
        return '';
    }


    public function teacherProfile()
    {
        return $this->belongsTo(TeacherProfile::class, 'user_id', 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function file()
    {
        return $this->hasMany(InternalMessageFile::class, 'message_id', 'id');
    }

}
