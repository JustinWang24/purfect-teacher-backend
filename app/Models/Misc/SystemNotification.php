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

    const SCHOOL_EMPTY = 0; // 针对哪个学校 0 表示不针对学校

    const CATEGORY = [
        1=>'易码通',
        2=>'校园网',
        3=>'通知',
        4=>'公告',
        5=>'检查',
        6=>'课件',
        7=>'课程',
        8=>'考试',
        9=>'招生',
        10=>'申请',
        11=>'订单',
        12=>'值周',
        13=>'就业',
        14=>'选课',
        15=>'会员',
        16=>'签到',
        17=>'优惠券',
        18=>'绿色通道',
        19=>'退费',
        20=>'消息',
    ];

    protected $fillable = [
        'sender',
        'to',
        'type',
        'priority',
        'school_id',
        'content',
        'next_move',
        'title',
        'money',
    ];
}
