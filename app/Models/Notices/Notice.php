<?php


namespace App\Models\Notices;

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

    public static function allType()
    {
        return [
            self::TYPE_NOTIFY     => self::TYPE_NOTIFY_TEXT,
            self::TYPE_NOTICE     => self::TYPE_NOTICE_TEXT,
            self::TYPE_INSPECTION => self::TYPE_INSPECTION_TEXT,
        ];
    }


    public function getTypeText()
    {
        return self::allType()[$this->type];
    }
}
