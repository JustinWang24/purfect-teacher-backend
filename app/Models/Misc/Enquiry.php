<?php

namespace App\Models\Misc;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enquiry extends Model
{
    use SoftDeletes;

    const STATUS_WAITING = 0;   // 待审批
    const STATUS_APPROVED = 10; // 同意

    const RESULT_REJECTED = 0;  // 审批结果: 拒绝
    const RESULT_AGREED   = 1;  // 审批结果: 同意
    const RESULT_IN_PROGRESS   = 2;  // 审批结果: 在审批中

    // 申请类型的定义
    const TYPE_DAY_OFF          = 0; // 请假
    const TYPE_BUSINESS_TRIP    = 1; // 外出, 出差
    const TYPE_VOUCHER          = 2; // 报销
    const TYPE_STAMP_REQUEST    = 3; // 用章
    const TYPE_VEHICLE_REQUEST  = 4; // 用车
    const TYPE_PLACE_REQUEST    = 5; // 场地
    const TYPE_STATIONARY       = 6; // 物品领用
    const TYPE_OTHER            = 20; // 其他

    const TYPE_DAY_OFF_TEXT          = '请假'; // 请假
    const TYPE_BUSINESS_TRIP_TEXT    = '外出, 出差'; //
    const TYPE_VOUCHER_TEXT          = '报销'; //
    const TYPE_STAMP_REQUEST_TEXT    = '用章'; //
    const TYPE_VEHICLE_REQUEST_TEXT  = '用车'; //
    const TYPE_PLACE_REQUEST_TEXT    = '场地'; //
    const TYPE_STATIONARY_TEXT       = '物品领用'; //
    const TYPE_OTHER_TEXT            = '其他'; //


    protected $fillable = [
        'school_id',//归属于哪个学校
        'user_id',//由谁发起
        'grade_user_id',// 发起人的学校信息
        'type', // 请求的种类: 请假, 报销等
        'to_user_id', //表示直接发给这个人进行审批
        'copy_to_user_id', //表示同时抄送发给这个人进行审批
        'approved_by', // 最终的审批人, 决策人
        'title', // 审批的 Title
        'start_at', // 请求事件所关联的开始日期
        'end_at', // 请求事件所关联的结束日期
        'status', // 审批的状态
        'result', // 审批的结果
        'details', // 请求的描述, 申请人填写
        'notes', //审批最终结果的标注
    ];

    public $casts = [
        'start_at'=>'datetime',
        'end_at'=>'datetime',
    ];

    public static function AllTypes(){
        return [
            self::TYPE_DAY_OFF => self::TYPE_DAY_OFF_TEXT,
            self::TYPE_BUSINESS_TRIP => self::TYPE_BUSINESS_TRIP_TEXT,
            self::TYPE_VOUCHER => self::TYPE_VOUCHER_TEXT,
            self::TYPE_STAMP_REQUEST => self::TYPE_STAMP_REQUEST_TEXT,
            self::TYPE_VEHICLE_REQUEST => self::TYPE_VEHICLE_REQUEST_TEXT,
            self::TYPE_PLACE_REQUEST => self::TYPE_PLACE_REQUEST_TEXT,
            self::TYPE_STATIONARY => self::TYPE_STATIONARY_TEXT,
            self::TYPE_OTHER => self::TYPE_OTHER_TEXT,
        ];
    }

    /**
     * @return Carbon
     */
    public function getScheduledAt(){
        return $this->start_at;
    }

    /**
     * @return Carbon
     */
    public function getEndedAt(){
        return $this->end_at;
    }
}
