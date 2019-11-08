<?php
/**
 * 表示系统内部消息的模型
 */
namespace App\Models\Misc;

use Illuminate\Database\Eloquent\Model;

class SystemNotification extends Model
{
    const PRIORITY_LOW      = 0;// 一般消息
    const PRIORITY_MEDIUM   = 2;// 重要消息
    const PRIORITY_HIGH     = 4;// 紧急消息

    const TO_ALL            = 0;// To 所有人
    const FROM_SYSTEM       = 0;// 发自系统的广播消息
    const TYPE_NONE         = 0;// 消息类别: 无
    const TYPE_STUDENT_REGISTRATION = 0;// 消息类别: 学生填写招生报名表

    protected $fillable = [
        'sender',
        'to',
        'type',
        'priority',
        'school_id',
        'content',
        'next_move',
    ];
}
