<?php
/**
 * 表示系统内部消息的模型
 */
namespace App\Models\Misc;

use App\Models\Notices\Notice;
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

    //消息分类 用于客户端展示
    const STUDENT_CATEGORY_QRCODE = 101;//学生端一码通
    const STUDENT_CATEGORY_RECRUITMENT = 102;//学生端招生
    const STUDENT_CATEGORY_ENROLMENT = 103;//学生端迎新
    const STUDENT_CATEGORY_ATTENDANCE = 106;//学生端值周
    const STUDENT_CATEGORY_COURSE = 107;//学生端选课
    const STUDENT_CATEGORY_COURSEWARE = 108;//学生端课件
    const STUDENT_CATEGORY_COURSEINFO = 109;//学生端课程
    const STUDENT_CATEGORY_EXAM = 110;//学生端考试
    const STUDENT_CATEGORY_EXAMRESULT = 111;//学生端成绩
    const STUDENT_CATEGORY_SIGNIN = 112;//学生端签到
    const STUDENT_CATEGORY_ORDER = 113;//学生端订单
    const STUDENT_CATEGORY_JOB = 114;//学生端就业
    const STUDENT_CATEGORY_VIP = 115;//学生端会员
    const STUDENT_CATEGORY_COUPON = 116;//学生端优惠券
    const STUDENT_CATEGORY_MESSAGE = 117;//学生端消息

    const TEACHER_CATEGORY_ATTENDANCE = 201;//教师端值周
    const TEACHER_CATEGORY_OAATTENDANCE = 202;//教师端考勤
    const TEACHER_CATEGORY_APPLY = 204;//教师端申请
    const TEACHER_CATEGORY_MEETING = 205;//教师端会议
    const TEACHER_CATEGORY_PROJECT = 206;//教师端项目
    const TEACHER_CATEGORY_TASK = 207;//教师端任务
    const TEACHER_CATEGORY_IMAIL = 208;//教师端内部信
    const TEACHER_CATEGORY_DOCUMENT = 209;//教师端公文
    const TEACHER_CATEGORY_COURSEINFO = 210;//教师端课程
    const TEACHER_CATEGORY_EXAM = 211;//教师端考试
    const TEACHER_CATEGORY_COURSE = 212;//教师端选课
    const TEACHER_CATEGORY_APPLY_STUDENT = 213;//教师端学生审批
    const TEACHER_CATEGORY_QRCODE = 214;//教师端一码通
    const TEACHER_CATEGORY_MESSAGE = 216;//教师端消息

    const COMMON_CATEGORY_NOTICE_NOTIFY = 301;//通知
    const COMMON_CATEGORY_NOTICE_NOTICE = 302;//公告
    const COMMON_CATEGORY_NOTICE_INSPECTION = 303;//检查
    const COMMON_CATEGORY_WIFI = 304;//校园网
    const COMMON_CATEGORY_PIPELINE = 305;//审批 @TODO 工作流程加入事件监听后会拆分不同类型


    protected $fillable = [
        'sender',
        'to',
        'type',
        'priority',
        'school_id',
        'content',
        'next_move',
        'title',
        'category',
        'app_extra'
    ];

    public static function getNoticeTypeToCategory()
    {
        return [
            Notice::TYPE_NOTIFY => self::COMMON_CATEGORY_NOTICE_NOTIFY,
            Notice::TYPE_NOTICE => self::COMMON_CATEGORY_NOTICE_NOTICE,
            Notice::TYPE_INSPECTION => self::COMMON_CATEGORY_NOTICE_INSPECTION
        ];
    }
}
