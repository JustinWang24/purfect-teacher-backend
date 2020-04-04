<?php

namespace App\Models\RecruitStudent;

use App\Models\Schools\RecruitmentPlan;
use Illuminate\Database\Eloquent\Model;
use App\Models\Students\StudentProfile;
use App\Models\School;
use App\Models\Schools\Major;
use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegistrationInformatics extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id', 'school_id', 'major_id', 'name', 'status',
        'recruitment_plan_id', 'relocation_allowed', 'branchclass_at', 'note',
    ];

    const USELESS           = 0;    // 申请作废
    const WAITING           = 1;    // 等待审核
    const REFUSED           = 2;    // 报名审核被拒绝
    const PASSED            = 3;    // 报名审核已通过
    const WAIT_FOR_APPROVAL = 3;    // 录取时 待录取
    const REJECTED          = 4;    // 被拒绝录取
    const APPROVED          = 5;    // 被录取
    const ENROLLED          = 6;    // 已报到

    const USELESS_TEXT              = '申请作废';
    const WAITING_TEXT              = '等待审核';
    const REFUSED_TEXT              = '报名审核被拒绝';
    const PASSED_TEXT               = '报名审核已通过';
    const WAIT_FOR_APPROVAL_TEXT    = '审核通过待录取';
    const REJECTED_TEXT             = '被拒绝录取';
    const APPROVED_TEXT             = '已录取';
    const ENROLLED_TEXT             = '已报到';

    public static function AllStatusStudent(){
        return [
            self::USELESS => self::USELESS_TEXT,
            self::WAITING => self::WAITING_TEXT,
            self::REFUSED => self::REFUSED_TEXT,
            self::PASSED => self::PASSED_TEXT,
            self::REJECTED => self::REJECTED_TEXT,
            self::APPROVED => self::APPROVED_TEXT,
            self::ENROLLED => self::ENROLLED_TEXT,
        ];
    }

    /**
     * 下面是后加的状态，上面是之前开发的人设置的状态
     * message:  是app进入招生页面，点击专业按钮提示的文字，
     * message1: 是app进入招生页面显示的状态文字，
     * message2: 是app已报名列表显示状态文字
     * @var array
     */
    public static $messageArr = [
        0 => array('status' => 0, 'message' => '参数错误', 'message1' => '报名', 'message2' => '状态错误'),

        // 后台审核通过了 1待审核 2报名审核被拒绝 3报名审核已通过 4被拒绝录取 5被录取 6已报到
        1 => array('status' => 1, 'message' => '您已申请，不能重复申请', 'message1' => '已申请', 'message2' => '审核中'),
        2 => array('status' => 2, 'message' => '您提交的申请审核未通过', 'message1' => '未通过', 'message2' => '未通过'),
        3 => array('status' => 3, 'message' => '您申请的已通过，不能重复申请', 'message1' => '已报名', 'message2' => '已通过'),
        4 => array('status' => 4, 'message' => '您提交的申请审核未通过', 'message1' => '未通过', 'message2' => '未通过'),
        5 => array('status' => 5, 'message' => '您申请的已通过，不能重复申请', 'message1' => '已报名', 'message2' => '已通过'),
        6 => array('status' => 6, 'message' => '您申请的已通过，不能重复申请', 'message1' => '已报名', 'message2' => '已通过'),

        // 根据后台设置的专业申请人数，已满
        10 => array('status' => 10, 'message' => '招生人数已满', 'message1' => '人已满', 'message2' => '人已满'),
        // 账号从后台添加，已经关联专业了，不能再次在app申请专业
        11 => array('status' => 11, 'message' => '您已经是学校的学生，不能申请', 'message1' => '报名', 'message2' => '已通过'),
        // 招生未开始
        12 => array('status' => 12, 'message' => '招生未开始', 'message1' => '未开始', 'message2' => '未开始'),
        // 招生已结束
        13 => array('status' => 13, 'message' => '招生已结束', 'message1' => '已结束', 'message2' => '已结束'),


        100 => array('status' => 100, 'message' => '报名', 'message1' => '报名', 'message2' => '报名'),
    ];

    /**
     * 学生详情表
     */
    public function studentProfile()
    {
        return $this->belongsTo(StudentProfile::class, 'user_id', 'user_id');
    }

    /**
     * 学校表
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function school()
    {
        return $this->hasOne(School::class, 'id', 'school_id');
    }

    /**
     *专业表
     */
    public function  major()
    {
        return $this->hasOne(Major::class, 'id', 'major_id');
    }

    /**
     * 用户表
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 报名表关联的学生资料数据
     */
    public function profile()
    {
        return $this->belongsTo(StudentProfile::class,'user_id','user_id');
    }

    /**
     * 关联的报名计划
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan(){
        return $this->belongsTo(RecruitmentPlan::class,'recruitment_plan_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lastOperator(){
        return $this->belongsTo(User::class, 'last_updated_by');
    }

    /**
     * 获取状态文字
     * @return string
     */
    public function getStatusText(){
        return self::AllStatusStudent()[$this->status];
    }

    /**
     * 学生是否服从分配
     * @return boolean
     */
    public function isRelocationAllowed(){
        return $this->relocation_allowed;
    }
}
