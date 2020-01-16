<?php


namespace App\Models\Notices;

use App\Models\NetworkDisk\Media;
use App\User;
use Carbon\Carbon;
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


    // 阅读状态
    const UNREAD = 0;
    const READ = 1;

    public $hidden = ['updated_at'];

    public static function allType()
    {
        return [
            self::TYPE_NOTIFY     => self::TYPE_NOTIFY_TEXT,
            self::TYPE_NOTICE     => self::TYPE_NOTICE_TEXT,
            self::TYPE_INSPECTION => self::TYPE_INSPECTION_TEXT,
        ];
    }

    protected $fillable = [
        'school_id', 'title', 'content', 'organization_id',
        'image', 'release_time', 'note', 'inspect_id', 'type', 'user_id',
        'status'];

    public $casts = [
        'release_time'=>'datetime'
    ];

    public $media_field = ['url'];

    public $inspect_field = ['name'];

    public $attachment_field = ['*'];

    public function getTypeText()
    {
        return self::allType()[$this->type];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inspect()
    {
        return $this->hasOne(NoticeInspect::class,
            'id', 'inspect_id')
            ->select($this->inspect_field);
    }

    public function attachments()
    {
        return $this->hasMany(NoticeMedia::class)
            ->select($this->attachment_field);
    }

    public function getImageAttribute($value){
        if(!empty($value)){
            return asset($value);
        }
        return null;
    }

    /**
     * 查看当前用户该通识是否阅读
     * @param $userId
     * @return Model|\Illuminate\Database\Eloquent\Relations\HasOne|object|null
     */
    public function readLog($userId) {
        return $this->hasOne(NoticeReadLogs::class)->where('user_id',$userId)->first();
    }


    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }
}
