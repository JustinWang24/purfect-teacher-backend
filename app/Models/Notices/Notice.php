<?php


namespace App\Models\Notices;

use App\Models\NetworkDisk\Media;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    // 通知类型
    const TYPE_NOTIFY = 1;
    const TYPE_NOTICE = 2;
    const TYPE_INSPECTION = 3;

    const TYPE_NOTIFY_TEXT = '通知';
    const TYPE_NOTICE_TEXT = '公告';
    const TYPE_INSPECTION_TEXT = '检查';


    // 状态
    const STATUS_UNPUBLISHED = 0;
    const STATUS_PUBLISH     = 1;
    const STATUS_UNPUBLISHED_TEXT = '未发布';
    const STATUS_PUBLISH_TEXT     = '已发布';


    public static function allType()
    {
        return [
            self::TYPE_NOTIFY     => self::TYPE_NOTIFY_TEXT,
            self::TYPE_NOTICE     => self::TYPE_NOTICE_TEXT,
            self::TYPE_INSPECTION => self::TYPE_INSPECTION_TEXT,
        ];
    }

    protected $fillable = [
        'school_id', 'title', 'content', 'organization_id', 'media_id',
        'image', 'release_time', 'note', 'inspect_id', 'type', 'user_id',
        'status'];

    public $media_field = ['url'];

    public $inspect_field = ['name'];

    public function getTypeText()
    {
        return self::allType()[$this->type];
    }

    public function user()
    {
        return $this->hasOne(User::class,
            'id', 'user_id');
    }

    public function inspect()
    {
        return $this->hasOne(NoticeInspect::class,
            'id', 'inspect_id')->
        select($this->inspect_field);
    }

    public function noticeMedias()
    {
        return $this->hasMany(NoticeMedia::class);
    }


    public function image_media() {
        return $this->belongsTo(Media::class,'image')
            ->select($this->media_field);
    }
}
