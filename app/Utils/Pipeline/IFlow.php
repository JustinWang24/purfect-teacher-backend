<?php
/**
 * 流程审批的抽象设计
 * User: justinwang
 * Date: 1/12/19
 * Time: 11:17 PM
 */

namespace App\Utils\Pipeline;

use phpDocumentor\Reflection\Types\Self_;

interface IFlow extends IPersistent
{
    //展示位置
    const POSITION_1 = 1;
    const POSITION_1_TXT = '办公审批';
    const POSITION_2 = 2;
    const POSITION_2_TXT = '办事大厅';
    const POSITION_3 = 3;
    const POSITION_3_TXT = '系统流程';

    const BUSINESS_TYPE_MACADDRESS = 'attendance_macaddress';
    const BUSINESS_TYPE_CLOCKIN = 'attendance_clockin';
    const BUSINESS_TYPE_LEAVE = 'attendance_leave';
    const BUSINESS_TYPE_AWAY = 'attendance_away';
    const BUSINESS_TYPE_TRAVEL = 'attendance_travel';

    //关联业务
    const BUSINESS_ATTENDANCE_MACADDRESS = [
        'business' => self::BUSINESS_TYPE_MACADDRESS,
        'name' => '考勤修改Mac地址',
        'options' => [
            ['name' => '单行输入框', 'type' => 'input', 'readonly' => true, 'required' => 1, 'extra' => ['text' => '考勤组id'], 'uri' => 'attendance_id', 'title' => '考勤组id', 'tips' => '考勤组id'],
            ['name' => '单行输入框', 'type' => 'input', 'readonly' => false, 'required' => 1, 'extra' => ['text' => '新Mac地址'], 'uri' => 'mac_address', 'title' => '新Mac地址','tips' => '新Mac地址'],
        ]
    ];
    const BUSINESS_ATTENDANCE_CLOCKIN = [
        'business' => self::BUSINESS_TYPE_CLOCKIN,
        'name' => '考勤补卡',
        'options' => [
            ['name' => '单行输入框', 'type' => 'input', 'readonly' => true, 'required' => 1, 'extra' => ['text' => '考勤组id'], 'uri' => 'attendance_id', 'title' => '考勤组id', 'tips' => '考勤组id'],
            ['name' => '单行输入框', 'type' => 'input', 'readonly' => true, 'required' => 1, 'extra' => ['text' => '补卡日期'], 'uri' => 'day', 'title' => '补卡日期', 'tips' => '补卡日期'],
            ['name' => '单行输入框', 'type' => 'input', 'readonly' => true, 'required' => 1, 'extra' => ['text' => '补卡时间'], 'uri' => 'type', 'title' => '补卡时间', 'tips' => '补卡时间'],
        ]
    ];
    const BUSINESS_ATTENDANCE_LEAVE = [
        'business' => self::BUSINESS_TYPE_LEAVE,
        'name' => '请假',
        'options' => [
            ['name' => '日期区间', 'type' => 'date-date', 'readonly' => false, 'required' => 1, 'extra' => ['text' => null, 'dateType' => 1, 'showPickerS' => false, 'showPickerE' => false, 'title2' => '结束时间'], 'uri' => 'date_date', 'title' => '开始时间', 'tips' => '选择时间']
        ]
    ];
    const BUSINESS_ATTENDANCE_AWAY = [
        'business' => self::BUSINESS_TYPE_AWAY,
        'name' => '外出',
        'options' => [
            ['name' => '日期区间', 'type' => 'date-date', 'readonly' => false, 'required' => 1, 'extra' => ['text' => null, 'dateType' => 1, 'showPickerS' => false, 'showPickerE' => false, 'title2' => '结束时间'], 'uri' => 'date_date', 'title' => '开始时间', 'tips' => '选择时间']
        ]
    ];
    const BUSINESS_ATTENDANCE_TRAVEL = [
        'business' => self::BUSINESS_TYPE_TRAVEL,
        'name' => '出差',
        'options' => [
            ['name' => '日期区间', 'type' => 'date-date', 'readonly' => false, 'required' => 1, 'extra' => ['text' => null, 'dateType' => 1, 'showPickerS' => false, 'showPickerE' => false, 'title2' => '结束时间'], 'uri' => 'date_date', 'title' => '开始时间', 'tips' => '选择时间']
        ]
    ];



    //分类type_{position}_{num}={position}{num}
    const TYPE_1_01 = 101;
    const TYPE_1_01_TXT = '财务管理';
    const TYPE_1_02 = 102;
    const TYPE_1_02_TXT = '固定资产';
    const TYPE_1_03 = 103;
    const TYPE_1_03_TXT = '行政管理';

    const TYPE_2_01 = 201;
    const TYPE_2_01_TXT = '日常申请';
    const TYPE_2_02 = 202;
    const TYPE_2_02_TXT = '资助中心';

    const TYPE_3_01 = 301;
    const TYPE_3_01_TXT = '考勤管理';
    const TYPE_3_02 = 302;
    const TYPE_3_02_TXT = '日常审批';


    //@TODO pipeline待删除 以下随便定义了几个流程的分类
    const TYPE_OFFICE = 1;
    const TYPE_2 = 2;
    const TYPE_3 = 3;
    const TYPE_4 = 4;
    const TYPE_TEACHER_ONLY = 5;

    const TYPE_STUDENT_ONLY = 6;
    const TYPE_FINANCE      = 7;
    const TYPE_STUDENT_COMMON = 8;

    const TYPE_OFFICE_TXT = '行政管理';
    const TYPE_2_TXT = '流程应用';
    const TYPE_3_TXT = '内外勤管理';
    const TYPE_4_TXT = '公文流转';
    const TYPE_TEACHER_ONLY_TXT = '教师专用';

    // 这三个是学生版办事大厅的种类
    const TYPE_STUDENT_ONLY_TXT = '学生专用';
    const TYPE_FINANCE_TXT      = '资助中心';
    const TYPE_STUDENT_COMMON_TXT  = '日常申请';

    /**
     * 获取流程的第一步
     * @return INode|null
     */
    public function getHeadNode();

    /**
     * 获取流程的最后一步
     * @return INode|null
     */
    public function getTailNode();

    /**
     * 获取流程名称
     * @return string
     */
    public function getName();

    /**
     * 流程是否可以被指定的用户 user 启动
     * @param IUser $user
     * @return INode
     */
    public function canBeStartBy(IUser $user): INode;

    /**
     * 返回当前流程中等待处理的步骤
     * @param IUser $user
     * @return IAction
     */
    public function getCurrentPendingAction(IUser $user): IAction;

    /**
     * 设置传入的 node 为当前流程
     *
     * @param INode $node
     * @param IUser $user
     * @return boolean
     */
    public function setCurrentPendingNode(INode $node, IUser $user);
}
