<?php
/**
 * 流程审批的抽象设计
 * User: justinwang
 * Date: 1/12/19
 * Time: 11:17 PM
 */

namespace App\Utils\Pipeline;

interface IFlow extends IPersistent
{
    //展示位置
    const POSITION_1 = 1;
    const POSITION_1_TXT = '办公审批';
    const POSITION_2 = 2;
    const POSITION_2_TXT = '办事大厅';
    const POSITION_3 = 3;
    const POSITION_3_TXT = '系统流程';

    //关联业务
    const BUSINESS_ATTENDANCE_MACADDRESS = [
        'business' => 'attendance_macaddress',
        'name' => '考勤修改Mac地址',
        'options' => [
            ['name' => '考勤组ID', 'type' => 'hidden', 'tip' => '请选择考勤组', 'required' => 1],
            ['name' => 'Mac地址', 'type' => 'text', 'tip' => '请输入新的Mac地址', 'required' => 1],
        ]
    ];
    const BUSINESS_ATTENDANCE_CLOCKIN = [
        'business' => 'attendance_clockin',
        'name' => '考勤补卡',
        'options' => [
            ['name' => '考勤组ID', 'type' => 'hidden', 'tip' => '请选择考勤组', 'extra' => '', 'required' => 1],
            ['name' => '补卡日期', 'type' => 'date', 'tip' => '请选择补卡日期', 'extra' => '', 'required' => 1],
            ['name' => '补卡类型', 'type' => 'radio', 'tip' => '请选择补卡类型', 'extra' => [['id'=> 'morning', 'option' => '早上'],['id'=> 'afternoon', 'option' => '中午'],['id'=> 'evening', 'option' => '晚上']], 'required' => 1],
            ['name' => '补卡原因', 'type' => 'text', 'tip' => '请输入补卡原因', 'extra' => '', 'required' => 1],
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
