<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 29/10/19
 * Time: 7:22 AM
 */

namespace App\Dao\Misc;
use App\Dao\Users\UserDao;
use App\Models\Misc\Enquiry;
use App\Utils\Time\GradeAndYearUtil;

class EnquiryDao
{

    public function __construct()
    {
    }

    /**
     * @param $data
     * @param $user
     * @return Enquiry|null
     */
    public function createEnquiry($data, $user){
        $userDao = new UserDao();

        if(!is_object($user))
            $user = $userDao->getUserByIdOrUuid($user);

        $bean = [
            'school_id' => $data['school_id'],//归属于哪个学校
            'user_id' => $user->id,//由谁发起
            'grade_user_id' => $data['data']['id'],// 发起人的学校信息
            'type' => $data['type'], // 请求的种类: 请假, 报销等
            'to_user_id' => $data['data']['teacher_id'], //表示直接发给这个人进行审批
            'copy_to_user_id' => $data['data']['teacher_id'], //表示同时抄送发给这个人进行审批
            'approved_by' => 0, // 最终的审批人, 决策人
            'title' => $data['title'], // 审批的 Title
            'start_at' => GradeAndYearUtil::ConvertJsTimeToCarbon($data['start_at_date']), // 请求事件所关联的开始日期
            'end_at' => GradeAndYearUtil::ConvertJsTimeToCarbon($data['end_at_date']), // 请求事件所关联的结束日期
            'status' => Enquiry::STATUS_WAITING, // 审批的状态
            'result' => Enquiry::RESULT_IN_PROGRESS, // 审批的结果
            'details' => $data['details'], // 请求的描述, 申请人填写
            'notes' => '', //审批最终结果的标注
        ];

        return Enquiry::create($bean);
    }
}