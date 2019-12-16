<?php


namespace App\Models\Forum;

use App\User;
use App\Models\School;
use App\Models\Students\StudentProfile;
use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
    protected  $fillable = ['user_id', 'school_id', 'content',
        'see_num', 'type_id', 'is_up', 'status'];

    const DRIVER_LOCAL    = 1;
    const DRIVER_ALI_YUN  = 2;
    const DRIVER_QI_NIU   = 3;

    const TYPE_IMAGE    = 1;   // 图片文件类型
    const TYPE_VIDEO    = 2;  // 视频文件类型

    const DEFAULT_UPLOAD_PATH_PREFIX = 'public/forum/';    // 存放用户文件路径
    const DEFAULT_URL_PATH_PREFIX = '/storage/forum/';     // 对外的

    const STATUS_UNCHECKED = 0;
    const STATUS_REFUSE    = 1;
    const STATUS_PASS      = 2;

    const STATUS_UNCHECKED_TEXT = '待审核';
    const STATUS_REFUSE_TEXT    = '未通过';
    const STATUS_PASS_TEXT      = '已通过';

    const CLOSE = false; // 不推荐
    const OPEN = true;  // 推荐


    public $image_field = ['*'];

    public $type_field = ['*'];

    public $profile_field = ['*'];

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

    public function allStatus() {
        return [
            self::STATUS_UNCHECKED => self::STATUS_UNCHECKED_TEXT,
            self::STATUS_REFUSE    => self::STATUS_REFUSE_TEXT,
            self::STATUS_PASS      => self::STATUS_PASS_TEXT,
        ];
    }

    /**
     * 当前状态
     * @return mixed
     */
    public function statusText() {
        $allStatus = $this->allStatus();
        return $allStatus[$this->status];
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


    /**
     * 帖子类型表
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function forumType()
    {
        return $this->hasOne(ForumType::class,'id','type_id')->select($this->type_field);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function studentProfile()
    {
        return $this->belongsTo(StudentProfile::class,'user_id','user_id')->select($this->profile_field);
    }

    /**
     * 帖子评论
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function forumComment()
    {
        return $this->hasMany(ForumComment::class)->select($this->image_field);
    }

    /**
     * 帖子点赞
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function forumLike()
    {
        return $this->hasMany(ForumLike::class)->select($this->image_field);
    }

    /**
     * 图片
     */
    public function image()
    {
        return $this->hasMany(ForumImage::class,'forum_id', 'id')->select($this->image_field);
    }


    /**
     * 转换图片网络路径
     * @param $value
     * @return string
     */
    public static function getImageUrl($value)
    {
        return $value ? asset($value) : '';
    }
}
